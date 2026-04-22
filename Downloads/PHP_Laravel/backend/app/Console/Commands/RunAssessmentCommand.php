<?php

namespace App\Console\Commands;

use App\Services\AssessmentService;
use Illuminate\Console\Command;

class RunAssessmentCommand extends Command
{
    protected $signature = 'assessment:run {server_id?} {--auto}';
    protected $description = 'Run security assessment for servers';

    public function handle(AssessmentService $assessmentService)
    {
        $serverId = $this->argument('server_id');
        
        if ($serverId) {
            $this->info("Running assessment for server ID: {$serverId}");
            $result = $assessmentService->run($serverId);
            $this->info("Score: {$result['score']}%");
        } else {
            $this->info("Running assessment for all servers...");
            $results = $assessmentService->runAll();
            $this->table(['Server ID', 'Score', 'Status'], $results);
        }
        
        return Command::SUCCESS;
    }
}