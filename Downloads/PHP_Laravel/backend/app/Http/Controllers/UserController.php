<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return $this->success($users, 'Users retrieved');
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return $this->success($user, 'User created', 201);
    }

    public function show($id)
    {
        $user = User::with('role', 'permissions')->findOrFail($id);
        return $this->success($user, 'User retrieved');
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        return $this->success($user, 'User updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->success(null, 'User deleted');
    }

    public function assignRole($id, $roleId)
    {
        $user = User::findOrFail($id);
        $user->role_id = $roleId;
        $user->save();
        return $this->success($user, 'Role assigned');
    }

    public function permissions($id)
    {
        $user = User::findOrFail($id);
        $permissions = $user->getAllPermissions();
        return $this->success($permissions, 'User permissions retrieved');
    }
}