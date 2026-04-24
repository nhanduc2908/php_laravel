<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->success($roles, 'Roles retrieved');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
            'slug' => 'required|string|unique:roles',
            'description' => 'nullable|string'
        ]);

        $role = Role::create($request->all());
        return $this->success($role, 'Role created', 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions', 'users')->findOrFail($id);
        return $this->success($role, 'Role retrieved');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return $this->success($role, 'Role updated');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $this->success(null, 'Role deleted');
    }

    public function assignPermission($id, $permissionId)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->attach($permissionId);
        return $this->success($role, 'Permission assigned');
    }

    public function revokePermission($id, $permissionId)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach($permissionId);
        return $this->success($role, 'Permission revoked');
    }

    public function permissions($id)
    {
        $role = Role::findOrFail($id);
        return $this->success($role->permissions, 'Permissions retrieved');
    }
}