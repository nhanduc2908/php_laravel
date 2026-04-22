<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use App\Models\AssessmentReport;

class FullAssessmentFlowTest extends TestCase
{
    protected $admin;
    protected $securityOfficer;
    protected $adminToken;
    protected $officerToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->adminToken = auth()->login($this->admin);
        $this->officerToken = auth()->login($this->securityOfficer);
    }

    /**
     * @test
     * E2E: Complete assessment flow from server creation to report viewing
     */
    public function complete_assessment_flow()
    {
        // ========== STEP 1: Admin creates a server ==========
        $serverResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/servers', [
                'name' => 'E2E Production Server',
                'host' => '192.168.1.100',
                'port' => 22,
                'username' => 'admin',
                'password' => 'secure_password',
                'status' => 'active',
                'os_type' => 'Ubuntu 22.04'
            ]);

        $serverResponse->assertStatus(201);
        $serverId = $serverResponse->json('data.id');
        $this->assertDatabaseHas('servers', ['id' => $serverId, 'name' => 'E2E Production Server']);

        // ========== STEP 2: Security Officer views server list ==========
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson('/api/v1/servers');

        $listResponse->assertStatus(200);
        $servers = $listResponse->json('data.data');
        $this->assertNotEmpty($servers);

        // ========== STEP 3: Security Officer runs assessment ==========
        $criteria = Criteria::factory()->count(5)->create();
        $answers = [];
        foreach ($criteria as $index => $c) {
            $answers[] = [
                'criteria_id' => $c->id,
                'value' => $index < 3 ? 'yes' : 'no',
                'evidence' => $index < 3 ? 'System compliant' : 'Needs improvement',
                'note' => 'Test note for criteria ' . $c->code
            ];
        }

        $assessmentResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/assessments/run', [
                'server_id' => $serverId,
                'answers' => $answers
            ]);

        $assessmentResponse->assertStatus(200);
        $score = $assessmentResponse->json('data.total_score');
        $compliance = $assessmentResponse->json('data.compliance_percent');
        
        $this->assertIsNumeric($score);
        $this->assertIsNumeric($compliance);
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(100, $score);

        // ========== STEP 4: Verify assessment results saved ==========
        $this->assertDatabaseHas('assessment_results', ['server_id' => $serverId]);
        $this->assertDatabaseHas('assessment_reports', ['server_id' => $serverId]);

        // ========== STEP 5: Security Officer views assessment history ==========
        $historyResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/assessments/history?server_id={$serverId}");

        $historyResponse->assertStatus(200);
        $historyResponse->assertJsonStructure(['status', 'data' => ['data', 'total']]);

        // ========== STEP 6: Generate and download report ==========
        $reportResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/reports/generate', [
                'server_id' => $serverId,
                'type' => 'full',
                'format' => 'pdf'
            ]);

        $reportResponse->assertStatus(200);
        $reportId = $reportResponse->json('data.id');
        
        // Download report
        $downloadResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/reports/download/{$reportId}");

        $downloadResponse->assertStatus(200);

        // ========== STEP 7: View dashboard stats ==========
        $dashboardResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/dashboard/stats');

        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertJsonStructure([
            'status', 'data' => ['total_servers', 'total_assessments', 'compliance_rate']
        ]);

        // ========== STEP 8: Clean up - Delete server ==========
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/v1/servers/{$serverId}");

        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('servers', ['id' => $serverId]);
    }
}