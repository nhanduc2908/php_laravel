<?php

namespace App\Console\Commands;

use App\Services\ServerService;
use Illuminate\Console\Command;

class SyncServersCommand extends Command
{
    protected $signature = 'servers:sync {--source=api}';
    protected $description = 'Sync servers from external source';

    public function handle(ServerService $serverService)
    {
        $source = $this->option('source');
        
        $this->info("Syncing servers from {$source}...");
        
        $result = $serverService->syncFromExternal($source);
        
        $this->info("Added: {$result['added']}");
        $this->info("Updated: {$result['updated']}");
        $this->info("Removed: {$result['removed']}");
        
        return Command::SUCCESS;
    }
}