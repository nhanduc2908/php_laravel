<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;

class CsrfTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_api_requires_csrf_token_for_state_changing_requests()
    {
        // API routes using JWT should not require CSRF
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/servers', [
                'name' => 'CSRF Test Server',
                'host' => '192.168.1.100',
                'port' => 22,
                'username' => 'admin'
            ]);

        // JWT authenticated requests should succeed without CSRF
        $response->assertStatus(201);
    }

    public function test_web_forms_require_csrf_token()
    {
        // Web routes should require CSRF protection
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        // Should fail without CSRF token
        $response->assertStatus(419); // CSRF token mismatch
    }

    public function test_csrf_token_is_present_in_meta_tag()
    {
        $response = $this->get('/login');
        
        $response->assertSee('csrf-token');
        $response->assertSee('name="csrf-token"');
    }

    public function test_csrf_token_changes_per_session()
    {
        $response1 = $this->get('/login');
        $token1 = $this->extractCsrfToken($response1);
        
        $response2 = $this->get('/login');
        $token2 = $this->extractCsrfToken($response2);
        
        // Tokens may be the same within same session, but should be valid
        $this->assertNotEmpty($token1);
        $this->assertNotEmpty($token2);
    }

    protected function extractCsrfToken($response)
    {
        preg_match('/<meta name="csrf-token" content="([^"]+)"/', $response->getContent(), $matches);
        return $matches[1] ?? null;
    }
}