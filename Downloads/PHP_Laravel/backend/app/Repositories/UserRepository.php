<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function getByRole($roleId, $perPage = 15)
    {
        return $this->model->where('role_id', $roleId)->paginate($perPage);
    }

    public function getActiveUsers($perPage = 15)
    {
        return $this->model->where('is_active', true)->paginate($perPage);
    }

    public function search($keyword, $perPage = 15)
    {
        return $this->model->where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function updateLastLogin($userId, $ip)
    {
        return $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip
        ], $userId);
    }

    public function getUsersWithRole($perPage = 15)
    {
        return $this->model->with('role')->paginate($perPage);
    }

    public function countByRole($roleId)
    {
        return $this->model->where('role_id', $roleId)->count();
    }

    public function getInactiveUsers($days = 30)
    {
        return $this->model->where('last_login_at', '<', now()->subDays($days))
            ->orWhereNull('last_login_at')
            ->get();
    }
}