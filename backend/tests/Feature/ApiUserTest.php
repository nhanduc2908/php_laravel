<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ApiUserTest extends TestCase
{
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->actingAsAdmin();
    }

    public function test_can_get_users_list()
    {
        User::factory()->count(5)->create();
        
        $response = $this->getJson('/api/v1/users');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_create_user()
    {
        $role = Role::factory()->create();
        
        $response = $this->postJson('/api/v1/users', [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id
        ]);
        
        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        
        $response = $this->putJson("/api/v1/users/{$user->id}", [
            'name' => 'New Name'
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        
        $response = $this->deleteJson("/api/v1/users/{$user->id}");
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}