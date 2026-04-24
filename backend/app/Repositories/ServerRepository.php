<?php

namespace App\Repositories;

use App\Models\Server;

class ServerRepository extends BaseRepository
{
    public function model()
    {
        return Server::class;
    }

    public function getByStatus($status, $perPage = 15)
    {
        return $this->model->where('status', $status)->paginate($perPage);
    }

    public function getActiveServers()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByUser($userId, $perPage = 15)
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->paginate($perPage);
    }

    public function search($keyword, $perPage = 15)
    {
        return $this->model->where('name', 'like', "%{$keyword}%")
            ->orWhere('host', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function getServersWithAssessments($perPage = 15)
    {
        return $this->model->with('assessments')->paginate($perPage);
    }

    public function updateScanTime($serverId)
    {
        return $this->update(['last_scan_at' => now()], $serverId);
    }

    public function getServersNeedingScan($days = 7)
    {
        return $this->model->where('last_scan_at', '<', now()->subDays($days))
            ->orWhereNull('last_scan_at')
            ->get();
    }

    public function getServerStats()
    {
        return [
            'total' => $this->model->count(),
            'active' => $this->model->where('status', 'active')->count(),
            'inactive' => $this->model->where('status', 'inactive')->count(),
            'pending' => $this->model->where('status', 'pending')->count()
        ];
    }
}