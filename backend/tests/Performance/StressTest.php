<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StressTest extends TestCase
{
    protected $concurrentRequests = 50;
    protected $totalRequests = 500;

    protected function tearDown(): void
    {
        Log::channel('performance')->info('Stress test completed', [
            'concurrent_requests' => $this->concurrentRequests,
            'total_requests' => $this->totalRequests
        ]);
        parent::tearDown();
    }

    public function test_high_concurrency_api_requests()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $promises = [];
        $startTime = microtime(true);
        
        for ($i = 0; $i < $this->totalRequests; $i++) {
            $promises[] = $this->withHeader('Authorization', 'Bearer ' . $token)
                              ->getJson('/api/v1/dashboard/stats');
        }
        
        $responses = [];
        foreach ($promises as $promise) {
            $responses[] = $promise;
        }
        
        $duration = (microtime(true) - $startTime) * 1000;
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($responses as $response) {
            if ($response->status() === 200) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }
        
        $throughput = ($this->totalRequests / ($duration / 1000));
        
        $this->results = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'throughput_rps' => round($throughput, 2),
            'success_rate' => round(($successCount / $this->totalRequests) * 100, 2)
        ];
        
        $this->assertGreaterThan(0.9, $this->results['success_rate'] / 100, 
            'Success rate below 90% under stress');
    }

    public function test_memory_usage_under_stress()
    {
        $initialMemory = memory_get_usage();
        $peakMemory = $initialMemory;
        
        for ($i = 0; $i < 100; $i++) {
            $response = $this->getJson('/api/v1/servers');
            $response->assertStatus(200);
            
            $currentMemory = memory_get_usage();
            if ($currentMemory > $peakMemory) {
                $peakMemory = $currentMemory;
            }
        }
        
        $memoryIncrease = ($peakMemory - $initialMemory) / 1024 / 1024;
        
        $this->results['memory_usage'] = [
            'initial_mb' => round($initialMemory / 1024 / 1024, 2),
            'peak_mb' => round($peakMemory / 1024 / 1024, 2),
            'increase_mb' => round($memoryIncrease, 2)
        ];
        
        $this->assertLessThan(50, $memoryIncrease, 'Memory increase exceeds 50MB under stress');
    }

    public function test_database_connection_pool_stress()
    {
        $startTime = microtime(true);
        $queries = [];
        
        for ($i = 0; $i < 200; $i++) {
            $queryStart = microtime(true);
            $users = \App\Models\User::take(10)->get();
            $queryDuration = (microtime(true) - $queryStart) * 1000;
            $queries[] = $queryDuration;
        }
        
        $avgQueryTime = array_sum($queries) / count($queries);
        $maxQueryTime = max($queries);
        
        $this->results['database_stress'] = [
            'avg_query_ms' => round($avgQueryTime, 2),
            'max_query_ms' => round($maxQueryTime, 2),
            'total_queries' => count($queries)
        ];
        
        $this->assertLessThan(100, $avgQueryTime, 'Average query time exceeds 100ms under stress');
    }
}