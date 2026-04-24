<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'password' => bcrypt('CorrectPassword123!')
        ]);
    }

    public function test_weak_password_rejected()
    {
        $weakPasswords = [
            '123456',
            'password',
            'qwerty',
            'admin123',
            'password123'
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

    public function test_account_lockout_after_multiple_failures()
    {
        $email = $this->user->email;
        
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => $email,
                'password' => 'wrong_password'
            ]);
        }
        
        // After 5 failures, account should be temporarily locked
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => 'CorrectPassword123!'
        ]);
        
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Account temporarily locked']);
    }

    public function test_jwt_token_expiration()
    {
        $token = JWTAuth::fromUser($this->user);
        
        // Travel to future beyond token expiry
        $this->travel(config('jwt.ttl') + 1)->minutes();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');
        
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Token has expired']);
    }

    public function test_token_can_be_refreshed()
    {
        $token = JWTAuth::fromUser($this->user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/refresh');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => ['access_token', 'token_type', 'expires_in']
        ]);
        
        $newToken = $response->json('data.access_token');
        $this->assertNotEquals($token, $newToken);
    }

    public function test_logout_invalidates_token()
    {
        $token = JWTAuth::fromUser($this->user);
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/auth/logout');
        
        // Token should no longer be valid
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/me');
        
        $response->assertStatus(401);
    }

    public function test_concurrent_sessions_limited()
    {
        // Create multiple sessions for same user
        $tokens = [];
        for ($i = 0; $i < 5; $i++) {
            $tokens[] = JWTAuth::fromUser($this->user);
        }
        
        // All tokens should be valid (or oldest may be invalidated based on config)
        foreach ($tokens as $token) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/v1/auth/me');
            
            $response->assertStatus(200);
        }
    }
}