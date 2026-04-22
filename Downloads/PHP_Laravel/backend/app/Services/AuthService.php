<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthService
{
    public function login($email, $password, $ip = null)
    {
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
        
        if (!$user->is_active) {
            return ['success' => false, 'message' => 'Account is disabled'];
        }
        
        $token = JWTAuth::fromUser($user);
        
        // Update last login info
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip
        ]);
        
        return [
            'success' => true,
            'token' => $token,
            'user' => $user,
            'expires_in' => config('jwt.ttl', 3600)
        ];
    }
    
    public function logout($token)
    {
        JWTAuth::invalidate($token);
        return ['success' => true, 'message' => 'Logged out'];
    }
    
    public function refreshToken($token)
    {
        $newToken = JWTAuth::refresh($token);
        return [
            'success' => true,
            'token' => $newToken,
            'expires_in' => config('jwt.ttl', 3600)
        ];
    }
    
    public function generateTwoFactorSecret($user)
    {
        $secret = Str::random(32);
        $user->update(['two_factor_secret' => $secret]);
        return $secret;
    }
    
    public function verifyTwoFactor($user, $code)
    {
        // Simple TOTP verification
        $expected = hash_hmac('sha1', floor(time() / 30), $user->two_factor_secret);
        return hash_equals($expected, $code);
    }
}