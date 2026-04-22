<?php

namespace App\Repositories;

use App\Models\Alert;

class AlertRepository extends BaseRepository
{
    public function model()
    {
        return Alert::class;
    }

    public function getUnread($userId = null, $perPage = 20)
    {
        $query = $this->model->where('is_read', false);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getBySeverity($severity, $perPage = 20)
    {
        return $this->model->where('severity', $severity)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function markAsRead($id)
    {
        return $this->update(['is_read' => true], $id);
    }

    public function markAllAsRead($userId = null)
    {
        $query = $this->model->where('is_read', false);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->update(['is_read' => true]);
    }

    public function resolve($id, $userId, $note = null)
    {
        return $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId,
            'resolution_note' => $note
        ], $id);
    }

    public function getStats()
    {
        return [
            'total' => $this->model->count(),
            'unread' => $this->model->where('is_read', false)->count(),
            'resolved' => $this->model->where('is_resolved', true)->count(),
            'by_severity' => $this->model->selectRaw('severity, COUNT(*) as count')
                ->groupBy('severity')
                ->get()
        ];
    }

    public function cleanOld($days = 30)
    {
        return $this->model->where('created_at', '<', now()->subDays($days))
            ->where('is_resolved', true)
            ->delete();
    }
}