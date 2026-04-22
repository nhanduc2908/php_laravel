<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiAuthTest extends TestCase
{
    public function test_user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['access_token', 'token_type', 'expires_in', 'user']
                 ]);
    }

    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_protected_route_requires_auth()
    {
        $response = $this->getJson('/api/v1/users');
        
        $response->assertStatus(401);
    }

    public function test_valid_token_can_access_protected_route()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/auth/me');
        
        $response->assertStatus(200)
                 ->assertJson(['data' => ['email' => $user->email]]);
    }
}