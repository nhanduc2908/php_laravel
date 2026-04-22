<?php

namespace Tests\Regression;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentFile;
use App\Services\ScoreCalculatorService;

class BugFixesTest extends TestCase
{
    protected $admin;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->adminToken = auth()->login($this->admin);
    }

    /**
     * @test
     * Bug #101: Login fails when email contains uppercase letters
     */
    public function test_login_case_insensitive_email()
    {
        $user = User::factory()->create([
            'email' => 'TestUser@Example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    /**
     * @test
     * Bug #102: Assessment score calculation returns incorrect percentage
     */
    public function test_score_calculation_accuracy()
    {
        $calculator = new ScoreCalculatorService();
        $server = Server::factory()->create();
        
        $criteria1 = Criteria::factory()->create(['weight' => 10]);
        $criteria2 = Criteria::factory()->create(['weight' => 20]);
        $criteria3 = Criteria::factory()->create(['weight' => 30]);
        
        \App\Models\AssessmentResult::create([
            'server_id' => $server->id,
            'criteria_id' => $criteria1->id,
            'score' => 10
        ]);
        
        \App\Models\AssessmentResult::create([
            'server_id' => $server->id,
            'criteria_id' => $criteria2->id,
            'score' => 10
        ]);
        
        \App\Models\AssessmentResult::create([
            'server_id' => $server->id,
            'criteria_id' => $criteria3->id,
            'score' => 15
        ]);
        
        $result = $calculator->calculateScore($server);
        
        $expectedScore = (10 + 10 + 15) / 60 * 100;
        $this->assertEquals(round($expectedScore, 2), $result['total_score']);
    }

    /**
     * @test
     * Bug #103: File sharing permissions not respected
     */
    public function test_file_sharing_permissions()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $file = AssessmentFile::factory()->create(['created_by' => $owner->id]);
        
        // Share file with other user (view only)
        $file->shares()->create([
            'shared_with' => $otherUser->id,
            'permission' => 'view'
        ]);
        
        $otherToken = auth()->login($otherUser);
        
        // Other user should be able to view
        $response = $this->withHeader('Authorization', 'Bearer ' . $otherToken)
            ->getJson("/api/v1/assessment-files/{$file->id}");
        
        $response->assertStatus(200);
        
        // Other user should NOT be able to edit
        $response = $this->withHeader('Authorization', 'Bearer ' . $otherToken)
            ->putJson("/api/v1/assessment-files/{$file->id}", [
                'title' => 'Hacked Title'
            ]);
        
        $response->assertStatus(403);
    }

    /**
     * @test
     * Bug #104: Server scan fails when SSH key has newlines
     */
    public function test_ssh_key_with_newlines()
    {
        $sshKey = "-----BEGIN RSA PRIVATE KEY-----\nMIIEpAIBAAKCAQEA...\n-----END RSA PRIVATE KEY-----\n";
        
        $server = Server::factory()->create([
            'ssh_key' => $sshKey,
            'password' => null
        ]);
        
        $this->assertNotNull($server->ssh_key);
        $this->assertStringContainsString('BEGIN RSA PRIVATE KEY', $server->ssh_key);
    }

    /**
     * @test
     * Bug #105: Pagination returns duplicate records
     */
    public function test_pagination_no_duplicates()
    {
        $users = User::factory()->count(25)->create();
        
        $page1 = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users?page=1&per_page=10');
        
        $page2 = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users?page=2&per_page=10');
        
        $page1Ids = collect($page1->json('data.data'))->pluck('id');
        $page2Ids = collect($page2->json('data.data'))->pluck('id');
        
        $duplicates = $page1Ids->intersect($page2Ids);
        
        $this->assertEquals(0, $duplicates->count(), 'Duplicate records found across pages');
    }

    /**
     * @test
     * Bug #106: Empty search returns all results
     */
    public function test_empty_search_returns_all()
    {
        $allResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users');
        
        $searchResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users?search=');
        
        $allCount = count($allResponse->json('data.data'));
        $searchCount = count($searchResponse->json('data.data'));
        
        $this->assertEquals($allCount, $searchCount);
    }

    /**
     * @test
     * Bug #107: Date filtering across midnight works
     */
    public function test_date_filtering_across_midnight()
    {
        $startDate = '2024-01-01';
        $endDate = '2024-01-02';
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/audit/logs?start_date={$startDate}&end_date={$endDate}");
        
        $response->assertStatus(200);
        // Should not throw error
    }
}