<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class MiddlewareTest extends TestCase
{
    public function test_auth_middleware_protects_routes()
    {
        $response = $this->getJson('/api/v1/users');
        
        $response->assertStatus(401);
    }

    public function test_auth_middleware_allows_authenticated_requests()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/auth/me');
        
        $response->assertStatus(200);
    }

    public function test_permission_middleware_restricts_access()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/audit/logs');
        
        $response->assertStatus(403);
    }

    public function test_rate_limit_middleware_works()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        for ($i = 0; $i < 61; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                             ->getJson('/api/v1/dashboard/stats');
        }
        
        $response->assertStatus(429);
    }

    public function test_cors_middleware_adds_headers()
    {
        $response = $this->getJson('/api/v1/health', [
            'Origin' => 'http://example.com'
        ]);
        
        $response->assertHeader('Access-Control-Allow-Origin');
    }
}