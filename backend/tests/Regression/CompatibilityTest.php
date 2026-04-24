<?php

namespace Tests\Regression;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;

class CompatibilityTest extends TestCase
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
     * API backward compatibility with v1 clients
     */
    public function test_api_v1_backward_compatibility()
    {
        // Old client expects 'data' wrapper
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users');
        
        $response->assertStatus(200);
        $this->assertArrayHasKey('data', $response->json());
    }

    /**
     * @test
     * PHP 8.1 compatibility - named arguments not breaking
     */
    public function test_named_arguments_compatibility()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@compatibility.com'
        ]);
        
        // This should work regardless of PHP version
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
    }

    /**
     * @test
     * Database migration compatibility - old schema still works
     */
    public function test_old_migration_schema()
    {
        // Check that essential tables still have expected columns
        $columns = \Schema::getColumnListing('users');
        
        $expectedColumns = ['id', 'name', 'email', 'password', 'role_id', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $column) {
            $this->assertContains($column, $columns, "Column {$column} missing from users table");
        }
    }

    /**
     * @test
     * Browser compatibility - response headers
     */
    public function test_browser_compatibility_headers()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/servers');
        
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    /**
     * @test
     * Mobile client compatibility - response size
     */
    public function test_mobile_client_response_size()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/servers?per_page=20');
        
        $contentSize = strlen($response->getContent());
        
        // Response should be under 1MB for mobile clients
        $this->assertLessThan(1048576, $contentSize, 'Response too large for mobile clients');
    }

    /**
     * @test
     * UTF-8 character compatibility
     */
    public function test_utf8_character_compatibility()
    {
        $unicodeText = 'Tiếng Việt có dấu, 日本語, English';
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/criteria', [
                'category_id' => 1,
                'code' => 'UTF8-TEST',
                'name' => $unicodeText,
                'weight' => 5
            ]);
        
        $response->assertStatus(201);
        
        $savedName = $response->json('data.name');
        $this->assertEquals($unicodeText, $savedName);
    }

    /**
     * @test
     * Timezone compatibility across different servers
     */
    public function test_timezone_compatibility()
    {
        $originalTz = date_default_timezone_get();
        date_default_timezone_set('America/New_York');
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200);
        
        date_default_timezone_set($originalTz);
    }
}