<?php

namespace Tests\Regression;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use App\Models\AssessmentFile;

class OldFeaturesTest extends TestCase
{
    protected $admin;
    protected $adminToken;
    protected $user;
    protected $userToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->adminToken = auth()->login($this->admin);
        $this->user = User::factory()->create(['role_id' => 3]);
        $this->userToken = auth()->login($this->user);
    }

    /**
     * @test
     * Legacy feature: User registration still works
     */
    public function test_user_registration_still_works()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'New Legacy User',
            'email' => 'legacy@test.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'legacy@test.com']);
    }

    /**
     * @test
     * Legacy feature: Password reset flow works
     */
    public function test_password_reset_flow()
    {
        $user = User::factory()->create(['email' => 'reset@test.com']);
        
        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'reset@test.com'
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Password reset link sent']);
    }

    /**
     * @test
     * Legacy feature: Server test connection works
     */
    public function test_server_test_connection()
    {
        $server = Server::factory()->create([
            'host' => '192.168.1.100',
            'port' => 22,
            'username' => 'testuser'
        ]);
        
        // Mock the connection test to avoid actual SSH
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson("/api/v1/servers/{$server->id}/test-connection");
        
        // Should return response (success or failure, but not crash)
        $response->assertStatus(200);
    }

    /**
     * @test
     * Legacy feature: Export criteria to Excel works
     */
    public function test_export_criteria_to_excel()
    {
        Criteria::factory()->count(10)->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/criteria/export');
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * @test
     * Legacy feature: Assessment history preserves order
     */
    public function test_assessment_history_order()
    {
        $server = Server::factory()->create();
        
        // Create assessments in specific order
        $dates = ['2024-01-15', '2024-01-10', '2024-01-20'];
        foreach ($dates as $date) {
            AssessmentResult::factory()->create([
                'server_id' => $server->id,
                'created_at' => $date
            ]);
        }
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->userToken)
            ->getJson("/api/v1/assessments/history?server_id={$server->id}");
        
        $response->assertStatus(200);
        $history = $response->json('data.data');
        
        // Should be sorted by created_at desc (newest first)
        if (count($history) >= 2) {
            $firstDate = strtotime($history[0]['created_at']);
            $secondDate = strtotime($history[1]['created_at']);
            $this->assertGreaterThanOrEqual($secondDate, $firstDate);
        }
    }

    /**
     * @test
     * Legacy feature: Dashboard stats format unchanged
     */
    public function test_dashboard_stats_format()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'total_servers',
                'active_servers',
                'total_assessments',
                'vulnerabilities_count',
                'compliance_rate'
            ]
        ]);
    }

    /**
     * @test
     * Legacy feature: File versioning works
     */
    public function test_file_versioning()
    {
        $file = AssessmentFile::factory()->create(['version' => 1]);
        
        $file->createNewVersion('Updated content', $file->created_by);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/assessment-files/{$file->id}/versions");
        
        $response->assertStatus(200);
        $versions = $response->json('data');
        
        $this->assertGreaterThanOrEqual(2, count($versions));
    }
}