<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class RBACTest extends TestCase
{
    public function test_user_has_role()
    {
        $role = Role::factory()->create(['slug' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id]);
        
        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('viewer'));
    }

    public function test_user_has_permission()
    {
        $permission = Permission::factory()->create(['slug' => 'view_users']);
        $role = Role::factory()->create();
        $role->permissions()->attach($permission);
        
        $user = User::factory()->create(['role_id' => $role->id]);
        
        $this->assertTrue($user->hasPermission('view_users'));
        $this->assertFalse($user->hasPermission('delete_users'));
    }

    public function test_super_admin_has_all_permissions()
    {
        $superAdminRole = Role::factory()->create(['slug' => 'super_admin']);
        $user = User::factory()->create(['role_id' => $superAdminRole->id]);
        
        $this->assertTrue($user->hasPermission('any_permission'));
    }

    public function test_role_can_be_assigned_to_user()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        
        $user->role_id = $role->id;
        $user->save();
        
        $this->assertEquals($role->id, $user->role_id);
    }

    public function test_permission_can_be_assigned_to_role()
    {
        $role = Role::factory()->create();
        $permission = Permission::factory()->create();
        
        $role->permissions()->attach($permission);
        
        $this->assertTrue($role->permissions->contains($permission));
    }
}