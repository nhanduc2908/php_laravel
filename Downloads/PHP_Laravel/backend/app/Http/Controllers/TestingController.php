<?php

namespace App\Http\Controllers;

use App\Services\TestRunnerService;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    protected $testRunner;

    public function __construct(TestRunnerService $testRunner)
    {
        $this->testRunner = $testRunner;
    }

    public function run(Request $request)
    {
        $request->validate([
            'suite' => 'nullable|string',
            'environment' => 'nullable|string'
        ]);

        $result = $this->testRunner->run($request->suite ?? 'all');
        return $this->success($result, 'Tests completed');
    }

    public function results()
    {
        $results = $this->testRunner->getResults();
        return $this->success($results, 'Test results');
    }

    public function coverage()
    {
        $coverage = $this->testRunner->getCoverage();
        return $this->success($coverage, 'Code coverage');
    }

    public function history()
    {
        $history = $this->testRunner->getHistory();
        return $this->success($history, 'Test history');
    }
}