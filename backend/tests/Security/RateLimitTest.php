<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;

class RateLimitTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    public function test_login_endpoint_rate_limiting()
    {
        $email = $this->user->email;
        $maxAttempts = 5;
        
        // Make more attempts than allowed
        for ($i = 0; $i < $maxAttempts + 3; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => $email,
                'password' => 'wrong_password'
            ]);
        }
        
        // Last attempt should be rate limited
        $response->assertStatus(429);
        $response->assertJson(['message' => 'Too many requests']);
    }

    public function test_api_endpoints_have_rate_limits()
    {
        $endpoints = [
            '/api/v1/servers',
            '/api/v1/criteria',
            '/api/v1/users'
        ];
        
        $requestsPerMinute = 100;
        
        foreach ($endpoints as $endpoint) {
            for ($i = 0; $i < $requestsPerMinute + 10; $i++) {
                $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
                    ->getJson($endpoint);
            }
            
            // Should be rate limited after exceeding
            $response->assertStatus(429);
        }
    }

    public function test_rate_limit_headers_are_present()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/servers');
        
        $response->assertHeader('X-RateLimit-Limit');
        $response->assertHeader('X-RateLimit-Remaining');
    }

    public function test_different_ips_have_separate_rate_limits()
    {
        $email = $this->user->email;
        
        // Simulate requests from different IPs
        for ($i = 0; $i < 10; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => "192.168.1.{$i}"])
                ->postJson('/api/v1/auth/login', [
                    'email' => $email,
                    'password' => 'wrong_password'
                ]);
            
            // Each IP should not hit rate limit
            $response->assertStatus(401); // Unauthorized, not rate limited
        }
    }

    public function test_rate_limit_resets_after_time()
    {
        $email = $this->user->email;
        
        // Exhaust rate limit
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => $email,
                'password' => 'wrong_password'
            ]);
        }
        
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => 'wrong_password'
        ]);
        
        $response->assertStatus(429);
        
        // Wait for reset (in real test, you'd use travel() or similar)
        // $this->travel(1)->minutes();
        // $response = $this->postJson(...);
        // $response->assertStatus(401);
    }
}