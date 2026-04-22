<?php

namespace App\Repositories;

use App\Models\TestResult;
use App\Models\TestSuite;

class TestRepository extends BaseRepository
{
    public function model()
    {
        return TestResult::class;
    }

    public function getResults($suite = null, $perPage = 20)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        
        if ($suite) {
            $query->where('test_suite', $suite);
        }
        
        return $query->paginate($perPage);
    }

    public function getLatestResults($limit = 10)
    {
        return $this->model->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    public function getCoverageStats()
    {
        return [
            'average_coverage' => $this->model->whereNotNull('coverage')->avg('coverage'),
            'last_coverage' => $this->model->whereNotNull('coverage')->latest()->first()->coverage ?? 0
        ];
    }

    public function getPassRate($suite = null)
    {
        $query = $this->model;
        
        if ($suite) {
            $query->where('test_suite', $suite);
        }
        
        $total = $query->count();
        $passed = $query->where('status', 'passed')->count();
        
        return $total > 0 ? round(($passed / $total) * 100, 2) : 0;
    }

    public function getTestSuites()
    {
        return TestSuite::all();
    }

    public function updateTestSuite($suiteName, $results)
    {
        $suite = TestSuite::firstOrCreate(['name' => $suiteName]);
        
        $suite->update([
            'tests_count' => $results['total'],
            'passed' => $results['passed'],
            'failed' => $results['failed'],
            'last_run_at' => now()
        ]);
        
        return $suite;
    }

    public function getTestHistory($days = 30)
    {
        return $this->model->selectRaw('DATE(created_at) as date, test_suite, COUNT(*) as total, SUM(CASE WHEN status = "passed" THEN 1 ELSE 0 END) as passed')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date', 'test_suite')
            ->orderBy('date', 'desc')
            ->get();
    }
}