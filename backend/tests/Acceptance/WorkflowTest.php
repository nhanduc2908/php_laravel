<?php

namespace Tests\Acceptance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;

class WorkflowTest extends TestCase
{
    protected $admin;
    protected $securityOfficer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
    }

    /**
     * @test
     * Complete assessment workflow from server creation to report generation
     */
    public function complete_assessment_workflow()
    {
        $adminToken = auth()->login($this->admin);
        $officerToken = auth()->login($this->securityOfficer);
        
        // Step 1: Admin creates a server
        $serverResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->postJson('/api/v1/servers', [
                'name' => 'Production Server',
                'host' => '192.168.1.100',
                'port' => 22,
                'username' => 'admin',
                'status' => 'active'
            ]);
        
        $serverResponse->assertStatus(201);
        $serverId = $serverResponse->json('data.id');
        
        // Step 2: Security Officer runs assessment
        $criteria = Criteria::factory()->count(3)->create();
        $answers = [];
        foreach ($criteria as $c) {
            $answers[] = ['criteria_id' => $c->id, 'value' => 'yes', 'evidence' => 'Compliant'];
        }
        
        $assessmentResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->postJson('/api/v1/assessments/run', [
                'server_id' => $serverId,
                'answers' => $answers
            ]);
        
        $assessmentResponse->assertStatus(200);
        $score = $assessmentResponse->json('data.total_score');
        
        // Step 3: Generate report
        $reportResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->postJson('/api/v1/reports/generate', [
                'server_id' => $serverId,
                'type' => 'full',
                'format' => 'pdf'
            ]);
        
        $reportResponse->assertStatus(200);
        $reportResponse->assertJsonStructure(['status', 'data' => ['filename', 'path']]);
        
        // Verify assessment results saved
        $this->assertDatabaseHas('assessment_results', ['server_id' => $serverId]);
        $this->assertDatabaseHas('assessment_reports', ['server_id' => $serverId]);
    }

    /**
     * @test
     * User management workflow
     */
    public function user_management_workflow()
    {
        $adminToken = auth()->login($this->admin);
        
        // Step 1: Create new user
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->postJson('/api/v1/users', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role_id' => 3
            ]);
        
        $createResponse->assertStatus(201);
        $userId = $createResponse->json('data.id');
        
        // Step 2: User logs in
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123!'
        ]);
        
        $loginResponse->assertStatus(200);
        $userToken = $loginResponse->json('data.access_token');
        
        // Step 3: User updates profile
        $profileResponse = $this->withHeader('Authorization', 'Bearer ' . $userToken)
            ->putJson('/api/v1/profile', [
                'name' => 'John Smith'
            ]);
        
        $profileResponse->assertStatus(200);
        
        // Step 4: Admin changes user role
        $roleResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->postJson("/api/v1/users/{$userId}/assign-role/4");
        
        $roleResponse->assertStatus(200);
        
        // Step 5: Admin deletes user
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->deleteJson("/api/v1/users/{$userId}");
        
        $deleteResponse->assertStatus(200);
    }

    /**
     * @test
     * File sharing workflow
     */
    public function file_sharing_workflow()
    {
        $adminToken = auth()->login($this->admin);
        $officerToken = auth()->login($this->securityOfficer);
        
        // Step 1: Security Officer creates a file
        $fileResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->postJson('/api/v1/assessment-files', [
                'title' => 'Confidential Report',
                'content' => 'Sensitive content here',
                'server_id' => 1,
                'status' => 'published'
            ]);
        
        $fileResponse->assertStatus(201);
        $fileId = $fileResponse->json('data.id');
        
        // Step 2: Share file with Admin
        $shareResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->postJson("/api/v1/assessment-files/{$fileId}/share/{$this->admin->id}");
        
        $shareResponse->assertStatus(200);
        
        // Step 3: Admin views shared file
        $viewResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->getJson("/api/v1/assessment-files/{$fileId}");
        
        $viewResponse->assertStatus(200);
        
        // Step 4: Create new version
        $versionResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->putJson("/api/v1/assessment-files/{$fileId}", [
                'title' => 'Updated Report',
                'content' => 'Updated content'
            ]);
        
        $versionResponse->assertStatus(200);
        
        // Step 5: Check versions history
        $historyResponse = $this->withHeader('Authorization', 'Bearer ' . $adminToken)
            ->getJson("/api/v1/assessment-files/{$fileId}/versions");
        
        $historyResponse->assertStatus(200);
        $historyResponse->assertJsonCount(2, 'data');
    }

    /**
     * @test
     * Vulnerability management workflow
     */
    public function vulnerability_management_workflow()
    {
        $officerToken = auth()->login($this->securityOfficer);
        $server = Server::factory()->create();
        
        // Step 1: Scan server (mock)
        $scanResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->postJson("/api/v1/servers/{$server->id}/scan");
        
        $scanResponse->assertStatus(200);
        
        // Step 2: View vulnerabilities
        $vulnResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
            ->getJson('/api/v1/vulnerabilities');
        
        $vulnResponse->assertStatus(200);
        
        // Step 3: Mark vulnerability as fixed
        if (!empty($vulnResponse->json('data'))) {
            $vulnId = $vulnResponse->json('data.0.id');
            $fixResponse = $this->withHeader('Authorization', 'Bearer ' . $officerToken)
                ->postJson("/api/v1/vulnerabilities/{$vulnId}/mark-fixed");
            
            $fixResponse->assertStatus(200);
        }
    }
}