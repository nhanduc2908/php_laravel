<?php

namespace Tests\Acceptance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentFile;

class UserStoryTest extends TestCase
{
    protected $admin;
    protected $securityOfficer;
    protected $viewer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->viewer = User::factory()->create(['role_id' => 4]);
    }

    /**
     * @test
     * User Story US-01: As a Security Officer, I want to run a security assessment on a server
     */
    public function security_officer_can_run_assessment()
    {
        $token = auth()->login($this->securityOfficer);
        $server = Server::factory()->create();
        $criteria = Criteria::factory()->count(5)->create();
        
        $answers = [];
        foreach ($criteria as $c) {
            $answers[] = [
                'criteria_id' => $c->id,
                'value' => 'yes',
                'evidence' => 'System is configured correctly'
            ];
        }
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/assessments/run', [
                'server_id' => $server->id,
                'answers' => $answers
            ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'data' => ['total_score', 'compliance_percent']
        ]);
        
        $this->assertDatabaseHas('assessment_results', ['server_id' => $server->id]);
    }

    /**
     * @test
     * User Story US-02: As a Security Officer, I want to upload evidence files for assessment
     */
    public function security_officer_can_upload_evidence_files()
    {
        $token = auth()->login($this->securityOfficer);
        $server = Server::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/assessment-files', [
                'title' => 'Security Evidence Q1',
                'content' => 'This document contains evidence of compliance...',
                'server_id' => $server->id,
                'status' => 'published'
            ]);
        
        $response->assertStatus(201);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('assessment_files', ['title' => 'Security Evidence Q1']);
    }

    /**
     * @test
     * User Story US-03: As an Admin, I want to manage user accounts
     */
    public function admin_can_manage_user_accounts()
    {
        $token = auth()->login($this->admin);
        
        // Create user
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/users', [
                'name' => 'New Staff',
                'email' => 'staff@company.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role_id' => 3
            ]);
        
        $createResponse->assertStatus(201);
        $userId = $createResponse->json('data.id');
        
        // Update user
        $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/v1/users/{$userId}", [
                'name' => 'Updated Staff Name'
            ]);
        
        $updateResponse->assertStatus(200);
        
        // Delete user
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/v1/users/{$userId}");
        
        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $userId]);
    }

    /**
     * @test
     * User Story US-04: As a Viewer, I want to view security reports
     */
    public function viewer_can_view_reports()
    {
        $token = auth()->login($this->viewer);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/reports');
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data']);
    }

    /**
     * @test
     * User Story US-05: As a Viewer, I should NOT be able to modify system settings
     */
    public function viewer_cannot_modify_system_settings()
    {
        $token = auth()->login($this->viewer);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/v1/settings', ['app_name' => 'Hacked Name']);
        
        $response->assertStatus(403);
    }

    /**
     * @test
     * User Story US-06: As a Security Officer, I want to view vulnerability scan results
     */
    public function security_officer_can_view_vulnerabilities()
    {
        $token = auth()->login($this->securityOfficer);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/vulnerabilities');
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data']);
    }

    /**
     * @test
     * User Story US-07: As an Auditor, I want to view all user activities
     */
    public function auditor_can_view_user_activities()
    {
        $auditor = User::factory()->create(['role_id' => 5]);
        $token = auth()->login($auditor);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/audit/logs');
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'data']);
    }
}