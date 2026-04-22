<?php

namespace App\Console\Commands;

use App\Services\TestRunnerService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test:run {--suite=all} {--coverage}';
    protected $description = 'Run automated tests';

    public function handle(TestRunnerService $testService)
    {
        $suite = $this->option('suite');
        $coverage = $this->option('coverage');
        
        $this->info("Running test suite: {$suite}");
        
        $results = $testService->run($suite, $coverage);
        
        $this->info("Tests: {$results['passed']}/{$results['total']} passed");
        
        if ($coverage) {
            $this->info("Coverage: {$results['coverage']}%");
        }
        
        return $results['failed'] === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}