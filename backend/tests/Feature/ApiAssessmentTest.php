<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;

class ApiAssessmentTest extends TestCase
{
    protected $securityOfficer;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityOfficer = $this->actingAsUser();
    }

    public function test_can_run_assessment()
    {
        $server = Server::factory()->create();
        $criteria = Criteria::factory()->count(3)->create();
        
        $answers = [];
        foreach ($criteria as $c) {
            $answers[] = [
                'criteria_id' => $c->id,
                'value' => 'yes',
                'evidence' => 'Test evidence'
            ];
        }
        
        $response = $this->postJson('/api/v1/assessments/run', [
            'server_id' => $server->id,
            'answers' => $answers
        ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status', 'data' => ['total_score', 'compliance_percent']
                 ]);
    }

    public function test_can_get_assessment_history()
    {
        $server = Server::factory()->create();
        
        $response = $this->getJson("/api/v1/assessments/history?server_id={$server->id}");
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_compliance_status()
    {
        $server = Server::factory()->create();
        
        $response = $this->getJson("/api/v1/assessments/compliance/{$server->id}");
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status', 'data' => ['total_criteria', 'compliant_criteria', 'compliance_rate']
                 ]);
    }

    public function test_can_export_assessment_result()
    {
        $assessment = AssessmentResult::factory()->create();
        
        $response = $this->getJson("/api/v1/assessments/{$assessment->id}/export");
        
        $response->assertStatus(200);
    }
}