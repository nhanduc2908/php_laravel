<?php

namespace App\Wrappers;

class AuthWrapper
{
    protected $user;

    public function attempt($credentials)
    {
        $user = (new QueryWrapper())->table('users')->where('email', $credentials['email'])->first();
        if ($user && password_verify($credentials['password'], $user['password'])) {
            $this->user = $user;
            $_SESSION['user_id'] = $user['id'];
            return true;
        }
        return false;
    }

    public function user()
    {
        if ($this->user) return $this->user;
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $this->user = (new QueryWrapper())->table('users')->where('id', $userId)->first();
            return $this->user;
        }
        return null;
    }

    public function check() { return $this->user() !== null; }
    public function id() { return $this->user()['id'] ?? null; }
    public function logout() { unset($_SESSION['user_id']); $this->user = null; }
}