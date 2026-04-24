<?php

namespace App\Repositories;

use App\Models\AssessmentFile;

class AssessmentFileRepository extends BaseRepository
{
    public function model()
    {
        return AssessmentFile::class;
    }

    public function getByServer($serverId, $perPage = 15)
    {
        return $this->model->where('server_id', $serverId)
            ->with('creator')
            ->paginate($perPage);
    }

    public function getByUser($userId, $perPage = 15)
    {
        return $this->model->where('created_by', $userId)
            ->orWhereHas('shares', function ($query) use ($userId) {
                $query->where('shared_with', $userId);
            })
            ->with('creator')
            ->paginate($perPage);
    }

    public function search($keyword, $perPage = 15)
    {
        return $this->model->where('title', 'like', "%{$keyword}%")
            ->orWhere('content', 'like', "%{$keyword}%")
            ->paginate($perPage);
    }

    public function getByStatus($status, $perPage = 15)
    {
        return $this->model->where('status', $status)->paginate($perPage);
    }

    public function getSharedWithUser($userId, $perPage = 15)
    {
        return $this->model->whereHas('shares', function ($query) use ($userId) {
            $query->where('shared_with', $userId);
        })->paginate($perPage);
    }

    public function getFilesWithDetails($perPage = 15)
    {
        return $this->model->with(['creator', 'server', 'shares.user'])
            ->paginate($perPage);
    }

    public function updateStatus($id, $status)
    {
        return $this->update(['status' => $status], $id);
    }

    public function getExpiredShares()
    {
        return $this->model->whereHas('shares', function ($query) {
            $query->where('expires_at', '<', now());
        })->get();
    }
}