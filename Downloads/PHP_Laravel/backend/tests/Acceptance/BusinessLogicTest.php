<?php

namespace Tests\Acceptance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use App\Services\ScoreCalculatorService;

class BusinessLogicTest extends TestCase
{
    protected $securityOfficer;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->token = auth()->login($this->securityOfficer);
    }

    /**
     * @test
     * Business Rule BR-01: Server must have unique name
     */
    public function server_name_must_be_unique()
    {
        Server::factory()->create(['name' => 'UniqueServer']);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/servers', [
                'name' => 'UniqueServer',
                'host' => '192.168.1.200',
                'port' => 22,
                'username' => 'admin'
            ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     * Business Rule BR-02: Assessment score cannot exceed 100%
     */
    public function assessment_score_cannot_exceed_100_percent()
    {
        $calculator = new ScoreCalculatorService();
        $server = Server::factory()->create();
        
        $criteria = Criteria::factory()->create(['weight' => 10]);
        
        AssessmentResult::create([
            'server_id' => $server->id,
            'criteria_id' => $criteria->id,
            'score' => 10
        ]);
        
        $result = $calculator->calculateScore($server);
        
        $this->assertLessThanOrEqual(100, $result['total_score']);
    }

    /**
     * @test
     * Business Rule BR-03: Only active users can log in
     */
    public function only_active_users_can_login()
    {
        $inactiveUser = User::factory()->create([
            'email' => 'inactive@test.com',
            'password' => bcrypt('password'),
            'is_active' => false
        ]);
        
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'inactive@test.com',
            'password' => 'password'
        ]);
        
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Account is disabled']);
    }

    /**
     * @test
     * Business Rule BR-04: File share expires after 30 days
     */
    public function file_share_expires_after_30_days()
    {
        $file = \App\Models\AssessmentFile::factory()->create();
        $otherUser = User::factory()->create();
        
        $share = $file->shares()->create([
            'shared_with' => $otherUser->id,
            'permission' => 'view',
            'expires_at' => now()->addDays(30)
        ]);
        
        $this->assertFalse($share->isExpired());
        
        // Simulate expiration
        $share->expires_at = now()->subDay();
        $this->assertTrue($share->isExpired());
    }

    /**
     * @test
     * Business Rule BR-05: Password must be at least 8 characters with complexity
     */
    public function password_complexity_requirements()
    {
        $weakPasswords = [
            'short',
            'nodigits!',
            'nouppercase1!',
            'NOLOWERCASE1!',
            'NoSpecialChar1'
        ];
        
        foreach ($weakPasswords as $password) {
            $response = $this->postJson('/api/v1/auth/register', [
                'name' => 'Test User',
                'email' => "test{$password}@example.com",
                'password' => $password,
                'password_confirmation' => $password
            ]);
            
            $response->assertStatus(422);
        }
    }

    /**
     * @test
     * Business Rule BR-06: Criteria weight must be between 1 and 10
     */
    public function criteria_weight_range()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/criteria', [
                'category_id' => 1,
                'code' => 'WEIGHT-TEST',
                'name' => 'Weight Test',
                'weight' => 15,
                'status' => 'active'
            ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['weight']);
    }

    /**
     * @test
     * Business Rule BR-07: Only Super Admin can delete system backups
     */
    public function only_super_admin_can_delete_backups()
    {
        $superAdmin = User::factory()->create(['role_id' => 1]);
        $superAdminToken = auth()->login($superAdmin);
        $regularAdmin = User::factory()->create(['role_id' => 2]);
        $regularAdminToken = auth()->login($regularAdmin);
        
        $backup = \App\Models\Backup::factory()->create();
        
        // Regular admin cannot delete
        $response = $this->withHeader('Authorization', 'Bearer ' . $regularAdminToken)
            ->deleteJson("/api/v1/backup/{$backup->id}");
        
        $response->assertStatus(403);
        
        // Super admin can delete
        $response = $this->withHeader('Authorization', 'Bearer ' . $superAdminToken)
            ->deleteJson("/api/v1/backup/{$backup->id}");
        
        $response->assertStatus(200);
    }

    /**
     * @test
     * Business Rule BR-08: Assessment must have evidence for non-compliant items
     */
    public function non_compliant_items_require_evidence()
    {
        $server = Server::factory()->create();
        $criteria = Criteria::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/assessments/run', [
                'server_id' => $server->id,
                'answers' => [
                    [
                        'criteria_id' => $criteria->id,
                        'value' => 'no',
                        'evidence' => ''  // Empty evidence for non-compliant
                    ]
                ]
            ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['answers.0.evidence']);
    }
}