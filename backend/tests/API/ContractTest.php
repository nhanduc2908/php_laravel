<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;

class ContractTest extends TestCase
{
    protected $user;
    protected $token;
    protected $admin;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->adminToken = auth()->login($this->admin);
    }

    public function test_login_response_contract()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => ['id', 'name', 'email', 'role_id']
                ],
                'timestamp'
            ]);
    }

    public function test_users_list_response_contract()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'data' => [
                    'data' => [
                        '*' => ['id', 'name', 'email', 'role_id', 'is_active', 'created_at']
                    ],
                    'current_page',
                    'total',
                    'per_page',
                    'last_page'
                ],
                'timestamp'
            ]);
    }

    public function test_server_create_response_contract()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/servers', [
                'name' => 'Contract Test Server',
                'host' => '192.168.1.100',
                'port' => 22,
                'username' => 'admin',
                'status' => 'active'
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'data' => ['id', 'name', 'host', 'port', 'username', 'status', 'created_at'],
                'timestamp'
            ]);
    }

    public function test_error_response_contract()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'wrong@test.com',
            'password' => 'wrong'
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'timestamp'
            ]);
    }

    public function test_validation_error_response_contract()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/v1/servers', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'code',
                'message',
                'errors' => ['name', 'host', 'port', 'username'],
                'timestamp'
            ]);
    }
}