<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupLogsCommand extends Command
{
    protected $signature = 'logs:clean {--days=30}';
    protected $description = 'Clean up old log files';

    public function handle()
    {
        $days = $this->option('days');
        $logPath = storage_path('logs');
        $cutoffDate = now()->subDays($days);
        
        $files = File::files($logPath);
        $deleted = 0;
        
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffDate->timestamp) {
                File::delete($file);
                $deleted++;
            }
        }
        
        $this->info("Deleted {$deleted} old log files");
        return Command::SUCCESS;
    }
}