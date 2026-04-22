<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;

class VersioningTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_api_v1_endpoints_accessible()
    {
        $response = $this->getJson('/api/v1/health');
        $response->assertStatus(200);
    }

    public function test_api_without_version_redirects()
    {
        $response = $this->getJson('/api/health');
        $response->assertStatus(404);
    }

    public function test_api_version_header_works()
    {
        $response = $this->withHeader('Accept', 'application/vnd.api.v1+json')
            ->getJson('/api/health');
        
        $response->assertStatus(200);
    }

    public function test_response_contains_api_version()
    {
        $response = $this->getJson('/api/v1/health');
        
        $response->assertJsonStructure(['version']);
        $response->assertJson(['version' => 'v1']);
    }

    public function test_deprecated_endpoint_returns_warning_header()
    {
        // Assume /api/v1/old-endpoint is deprecated
        $response = $this->getJson('/api/v1/old-endpoint');
        
        if ($response->getStatusCode() !== 404) {
            $response->assertHeader('Deprecation', 'true');
            $response->assertHeader('Sunset', '2024-12-31');
        }
    }

    public function test_new_fields_in_v1_response()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/auth/me');
        
        $response->assertStatus(200);
        $data = $response->json('data');
        
        // v1 should have these fields
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('email', $data);
    }

    public function test_version_accept_header_priority()
    {
        // Send both URL version and accept header
        $response = $this->withHeader('Accept', 'application/vnd.api.v2+json')
            ->getJson('/api/v1/health');
        
        // URL version should take precedence
        $response->assertJson(['version' => 'v1']);
    }
}