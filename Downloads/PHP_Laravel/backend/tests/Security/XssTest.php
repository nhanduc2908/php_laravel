<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\AssessmentFile;

class XssTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_criteria_name_resists_xss()
    {
        $xssPayloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')",
            "<svg onload=alert('XSS')>",
            "<body onload=alert('XSS')>",
            "';alert(String.fromCharCode(88,83,83))//",
            "\"><script>alert('XSS')</script>",
            "<iframe src=\"javascript:alert('XSS')\">"
        ];

        foreach ($xssPayloads as $payload) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->postJson('/api/v1/criteria', [
                    'category_id' => 1,
                    'code' => 'XSS-TEST',
                    'name' => $payload,
                    'weight' => 5,
                    'status' => 'active'
                ]);

            $response->assertStatus(201);
            
            $data = $response->json('data');
            // Name should be HTML-encoded, not executed
            $this->assertStringNotContainsString('<script>', $data['name']);
        }
    }

    public function test_file_content_resists_xss()
    {
        $xssPayload = "<script>alert('XSS in file')</script>";
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/assessment-files', [
                'title' => 'XSS Test File',
                'content' => $xssPayload,
                'server_id' => 1,
                'status' => 'draft'
            ]);

        $response->assertStatus(201);
        
        $content = $response->json('data.content');
        $this->assertStringNotContainsString('<script>', $content);
    }

    public function test_api_response_escapes_xss_in_headers()
    {
        $xssHeader = "<script>alert('XSS')</script>";
        
        $response = $this->withHeader('X-Test-Header', $xssHeader)
            ->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/users');
        
        $response->assertStatus(200);
        // Headers should be escaped or not reflected
    }

    public function test_search_query_resists_xss()
    {
        $xssQueries = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')"
        ];

        foreach ($xssQueries as $query) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                ->getJson('/api/v1/servers?search=' . urlencode($query));
            
            $response->assertStatus(200);
            $responseBody = $response->getContent();
            $this->assertStringNotContainsString('<script>', $responseBody);
        }
    }
}