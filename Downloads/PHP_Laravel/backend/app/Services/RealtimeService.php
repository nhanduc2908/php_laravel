<?php

namespace App\Services;

use App\Events\RealtimeEvent;
use Illuminate\Support\Facades\Redis;

class RealtimeService
{
    public function push($channel, $event, $data)
    {
        $payload = [
            'event' => $event,
            'data' => $data,
            'timestamp' => now()->toIso8601String()
        ];
        
        Redis::publish($channel, json_encode($payload));
        
        // Also fire Laravel event for logging
        event(new RealtimeEvent($channel, $event, $data));
        
        return true;
    }
    
    public function pushToUser($userId, $event, $data)
    {
        return $this->push("user.{$userId}", $event, $data);
    }
    
    public function pushToServer($serverId, $event, $data)
    {
        return $this->push("server.{$serverId}", $event, $data);
    }
    
    public function pushToAdmin($event, $data)
    {
        return $this->push("admin", $event, $data);
    }
    
    public function broadcastScanProgress($serverId, $progress, $message = null)
    {
        return $this->pushToServer($serverId, 'scan.progress', [
            'server_id' => $serverId,
            'progress' => $progress,
            'message' => $message ?? "Scan progress: {$progress}%"
        ]);
    }
    
    public function broadcastAssessmentComplete($assessmentId, $result)
    {
        return $this->pushToAdmin('assessment.completed', [
            'assessment_id' => $assessmentId,
            'result' => $result
        ]);
    }
    
    public function broadcastNewVulnerability($vulnerability)
    {
        return $this->pushToAdmin('vulnerability.new', $vulnerability);
    }
    
    public function broadcastAlert($alert)
    {
        return $this->pushToAdmin('alert.new', $alert);
    }
    
    public function broadcastFileShared($file, $sharedWith)
    {
        return $this->pushToUser($sharedWith, 'file.shared', [
            'file_id' => $file->id,
            'title' => $file->title,
            'shared_by' => $file->creator->name
        ]);
    }
}