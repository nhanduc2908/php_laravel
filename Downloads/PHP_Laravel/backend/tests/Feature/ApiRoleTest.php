<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\Permission;

class ApiRoleTest extends TestCase
{
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->actingAsAdmin();
    }

    public function test_can_get_roles_list()
    {
        Role::factory()->count(3)->create();
        
        $response = $this->getJson('/api/v1/roles');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_create_role()
    {
        $response = $this->postJson('/api/v1/roles', [
            'name' => 'Test Role',
            'slug' => 'test_role',
            'description' => 'Test description'
        ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', ['slug' => 'test_role']);
    }

    public function test_can_assign_permission_to_role()
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        
        $response = $this->postJson("/api/v1/roles/{$role->id}/permissions", [
            'permission_id' => $permission->id
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('role_permission', [
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);
    }
}