<?php

namespace App\Jobs;

use App\Services\TestRunnerService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class RunTestSuiteJob extends BaseJob
{
    protected $suite;
    protected $userId;

    public function __construct($suite = 'all', $userId = null)
    {
        $this->suite = $suite;
        $this->userId = $userId;
    }

    public function handle(TestRunnerService $testRunner, NotificationService $notification)
    {
        $start = microtime(true);
        
        $result = $testRunner->run($this->suite, false);
        
        $duration = round((microtime(true) - $start) * 1000, 2);
        
        if ($this->userId) {
            $status = $result['success'] ? 'passed' : 'failed';
            $notification->sendPush($this->userId, "Test Suite: {$this->suite}", "Tests {$status} in {$duration}ms", $result);
        }
        
        Log::info('Test suite executed', [
            'suite' => $this->suite,
            'success' => $result['success'],
            'duration' => $duration
        ]);
    }
}