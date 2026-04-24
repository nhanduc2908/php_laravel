<?php

namespace App\Jobs;

use App\Services\BackupService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class BackupDatabaseJob extends BaseJob
{
    protected $type;
    protected $userId;

    public function __construct($type = 'database', $userId = null)
    {
        $this->type = $type;
        $this->userId = $userId;
    }

    public function handle(BackupService $backup, NotificationService $notification)
    {
        try {
            $result = $backup->create($this->type);
            
            if ($this->userId) {
                $notification->sendPush($this->userId, 'Backup Complete', "Backup of type {$this->type} completed successfully", $result);
            }
            
            Log::info('Backup completed', ['type' => $this->type, 'size' => $result['size']]);
            
        } catch (\Exception $e) {
            Log::error('Backup failed', ['type' => $this->type, 'error' => $e->getMessage()]);
            
            if ($this->userId) {
                $notification->sendPush($this->userId, 'Backup Failed', 'Backup failed: ' . $e->getMessage());
            }
            
            throw $e;
        }
    }
}