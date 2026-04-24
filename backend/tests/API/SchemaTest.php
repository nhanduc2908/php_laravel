<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;

class SchemaTest extends TestCase
{
    protected $admin;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->adminToken = auth()->login($this->admin);
    }

    public function test_user_schema_matches_expected_fields()
    {
        $user = User::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $expectedFields = ['id', 'name', 'email', 'role_id', 'is_active', 'avatar', 'created_at'];
        foreach ($expectedFields as $field) {
            $this->assertArrayHasKey($field, $data);
        }
        
        // Type checking
        $this->assertIsInt($data['id']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['email']);
        $this->assertIsInt($data['role_id']);
        $this->assertIsBool($data['is_active']);
    }

    public function test_server_schema_matches_expected_fields()
    {
        $server = Server::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/servers/{$server->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $expectedFields = ['id', 'name', 'host', 'port', 'username', 'status', 'os_type', 'created_at'];
        foreach ($expectedFields as $field) {
            $this->assertArrayHasKey($field, $data);
        }
        
        $this->assertIsString($data['host']);
        $this->assertIsInt($data['port']);
        $this->assertContains($data['status'], ['pending', 'active', 'inactive']);
    }

    public function test_criteria_schema_matches_expected_fields()
    {
        $criteria = Criteria::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/criteria/{$criteria->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $expectedFields = ['id', 'category_id', 'code', 'name', 'weight', 'status', 'answer_type'];
        foreach ($expectedFields as $field) {
            $this->assertArrayHasKey($field, $data);
        }
        
        $this->assertIsFloat($data['weight']);
        $this->assertContains($data['status'], ['active', 'inactive']);
    }

    public function test_assessment_file_schema()
    {
        $file = \App\Models\AssessmentFile::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/assessment-files/{$file->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $expectedFields = ['id', 'title', 'content', 'server_id', 'created_by', 'status', 'version', 'created_at'];
        foreach ($expectedFields as $field) {
            $this->assertArrayHasKey($field, $data);
        }
    }

    public function test_pagination_schema()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users?page=1&per_page=15');

        $response->assertStatus(200);
        $meta = $response->json('meta');
        
        $this->assertArrayHasKey('pagination', $meta);
        $this->assertArrayHasKey('total', $meta['pagination']);
        $this->assertArrayHasKey('per_page', $meta['pagination']);
        $this->assertArrayHasKey('current_page', $meta['pagination']);
        $this->assertArrayHasKey('last_page', $meta['pagination']);
    }
}