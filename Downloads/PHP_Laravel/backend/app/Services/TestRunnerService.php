<?php

namespace App\Services;

use App\Models\TestResult;
use App\Models\TestSuite;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class TestRunnerService
{
    public function run($suite = 'all', $coverage = false)
    {
        $startTime = microtime(true);
        
        $command = $coverage ? 'test --coverage' : 'test';
        
        if ($suite != 'all') {
            $command .= " --testsuite={$suite}";
        }
        
        $exitCode = Artisan::call($command);
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        $output = Artisan::output();
        
        $this->parseResults($output, $suite, $duration);
        
        return [
            'success' => $exitCode === 0,
            'duration' => $duration,
            'output' => $output
        ];
    }
    
    public function getResults()
    {
        return TestResult::orderBy('created_at', 'desc')->paginate(20);
    }
    
    public function getCoverage()
    {
        $coverageFile = base_path('coverage/index.html');
        
        if (File::exists($coverageFile)) {
            return file_get_contents($coverageFile);
        }
        
        return null;
    }
    
    public function getHistory()
    {
        $results = TestResult::selectRaw('DATE(created_at) as date, test_suite, COUNT(*) as total, SUM(CASE WHEN status = "passed" THEN 1 ELSE 0 END) as passed')
            ->groupBy('date', 'test_suite')
            ->orderBy('date', 'desc')
            ->get();
        
        return $results;
    }
    
    protected function parseResults($output, $suite, $duration)
    {
        // Parse PHPUnit output
        preg_match_all('/OK \((\d+) tests, (\d+) assertions\)/', $output, $matches);
        
        if (!empty($matches[1])) {
            TestResult::create([
                'test_suite' => $suite,
                'status' => 'passed',
                'duration' => $duration,
                'message' => "All tests passed"
            ]);
        } else {
            preg_match('/Tests: (\d+) passed, (\d+) failed/', $output, $failMatches);
            $passed = $failMatches[1] ?? 0;
            $failed = $failMatches[2] ?? 0;
            
            TestResult::create([
                'test_suite' => $suite,
                'status' => $failed > 0 ? 'failed' : 'passed',
                'duration' => $duration,
                'message' => "{$passed} passed, {$failed} failed"
            ]);
        }
    }
}