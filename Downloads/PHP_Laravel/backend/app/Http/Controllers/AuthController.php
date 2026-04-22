<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl', 3600),
            'user' => Auth::user()
        ], 'Login successful');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id ?? 3
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer'
        ], 'Registration successful', 201);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->success(null, 'Logout successful');
    }

    public function refresh()
    {
        return $this->success([
            'access_token' => JWTAuth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl', 3600)
        ], 'Token refreshed');
    }

    public function me()
    {
        return $this->success(Auth::user(), 'User info retrieved');
    }
}