<?php

namespace App\Jobs;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CleanupLogsJob extends BaseJob
{
    protected $days;

    public function __construct($days = 30)
    {
        $this->days = $days;
    }

    public function handle()
    {
        $logPath = storage_path('logs');
        $cutoffDate = now()->subDays($this->days);
        
        $files = File::files($logPath);
        $deleted = 0;
        
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffDate->timestamp) {
                File::delete($file);
                $deleted++;
            }
        }
        
        // Also clean old backup logs
        $backupLogPath = storage_path('logs/backup');
        if (File::isDirectory($backupLogPath)) {
            $backupFiles = File::files($backupLogPath);
            foreach ($backupFiles as $file) {
                if ($file->getMTime() < $cutoffDate->timestamp) {
                    File::delete($file);
                    $deleted++;
                }
            }
        }
        
        Log::info('Log cleanup completed', ['deleted_files' => $deleted, 'days' => $this->days]);
    }
}