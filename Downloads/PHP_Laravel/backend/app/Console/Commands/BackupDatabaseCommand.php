<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:run {--database=mysql}';
    protected $description = 'Backup database';

    public function handle(BackupService $backupService)
    {
        $database = $this->option('database');
        
        $this->info("Starting backup for database: {$database}");
        
        $backup = $backupService->backup($database);
        
        if ($backup) {
            $this->info("Backup completed: {$backup['filename']}");
            $this->info("Size: {$backup['size']} MB");
        } else {
            $this->error("Backup failed!");
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}