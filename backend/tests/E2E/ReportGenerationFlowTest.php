<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentReport;

class ReportGenerationFlowTest extends TestCase
{
    protected $securityOfficer;
    protected $officerToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->officerToken = auth()->login($this->securityOfficer);
    }

    /**
     * @test
     * E2E: Complete report generation flow from assessment to export
     */
    public function complete_report_generation_flow()
    {
        // ========== STEP 1: Create server and run assessment ==========
        $server = Server::factory()->create(['name' => 'Report Test Server']);
        $criteria = Criteria::factory()->count(10)->create();
        
        $answers = [];
        foreach ($criteria as $c) {
            $answers[] = [
                'criteria_id' => $c->id,
                'value' => rand(0, 1) ? 'yes' : 'no',
                'evidence' => 'E2E test evidence'
            ];
        }

        $assessmentResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/assessments/run', [
                'server_id' => $server->id,
                'answers' => $answers
            ]);

        $assessmentResponse->assertStatus(200);
        $score = $assessmentResponse->json('data.total_score');

        // ========== STEP 2: Generate PDF report ==========
        $pdfResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/reports/generate', [
                'server_id' => $server->id,
                'type' => 'full',
                'format' => 'pdf'
            ]);

        $pdfResponse->assertStatus(200);
        $pdfResponse->assertJsonStructure(['status', 'data' => ['id', 'filename', 'path']]);
        $reportId = $pdfResponse->json('data.id');

        // ========== STEP 3: Generate Excel report ==========
        $excelResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/reports/generate', [
                'server_id' => $server->id,
                'type' => 'summary',
                'format' => 'excel'
            ]);

        $excelResponse->assertStatus(200);

        // ========== STEP 4: List reports ==========
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson('/api/v1/reports');

        $listResponse->assertStatus(200);
        $reports = $listResponse->json('data.data');
        $this->assertGreaterThanOrEqual(1, count($reports));

        // ========== STEP 5: Download PDF report ==========
        $downloadResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/reports/download/{$reportId}");

        $downloadResponse->assertStatus(200);

        // ========== STEP 6: Schedule recurring report ==========
        $scheduleResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/reports/schedule', [
                'server_id' => $server->id,
                'frequency' => 'weekly',
                'format' => 'pdf',
                'recipient_email' => 'admin@security.com'
            ]);

        $scheduleResponse->assertStatus(200);
        $this->assertDatabaseHas('report_schedules', ['server_id' => $server->id]);

        // ========== STEP 7: Export compliance data ==========
        $complianceResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/assessments/compliance/{$server->id}");

        $complianceResponse->assertStatus(200);
        $complianceResponse->assertJsonStructure(['data' => ['compliance_rate']]);

        // ========== STEP 8: Verify report contains correct score ==========
        $report = AssessmentReport::find($reportId);
        $this->assertEquals($score, $report->total_score);
    }
}