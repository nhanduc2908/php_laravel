<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use Illuminate\Support\Facades\Log;

class ApiPerformanceTest extends TestCase
{
    protected $results = [];
    protected $endpoints = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoints = [
            'GET /api/v1/dashboard/stats' => [],
            'GET /api/v1/servers' => [],
            'GET /api/v1/criteria' => [],
            'POST /api/v1/auth/login' => [],
            'GET /api/v1/reports' => []
        ];
    }

    protected function tearDown(): void
    {
        Log::channel('performance')->info('API performance test completed', $this->results);
        parent::tearDown();
    }

    public function test_api_response_times()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        // Test GET endpoints
        $getEndpoints = [
            '/api/v1/dashboard/stats',
            '/api/v1/servers',
            '/api/v1/criteria'
        ];
        
        foreach ($getEndpoints as $endpoint) {
            $times = [];
            for ($i = 0; $i < 10; $i++) {
                $start = microtime(true);
                $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                    ->getJson($endpoint);
                $duration = (microtime(true) - $start) * 1000;
                $times[] = $duration;
                $response->assertStatus(200);
            }
            
            $this->endpoints["GET {$endpoint}"] = [
                'avg_ms' => round(array_sum($times) / count($times), 2),
                'min_ms' => round(min($times), 2),
                'max_ms' => round(max($times), 2),
                'p95_ms' => round($this->percentile($times, 95), 2)
            ];
        }
        
        // Test POST endpoint
        $loginTimes = [];
        for ($i = 0; $i < 10; $i++) {
            $start = microtime(true);
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'password'
            ]);
            $duration = (microtime(true) - $start) * 1000;
            $loginTimes[] = $duration;
            $response->assertStatus(200);
        }
        
        $this->endpoints['POST /api/v1/auth/login'] = [
            'avg_ms' => round(array_sum($loginTimes) / count($loginTimes), 2),
            'min_ms' => round(min($loginTimes), 2),
            'max_ms' => round(max($loginTimes), 2),
            'p95_ms' => round($this->percentile($loginTimes, 95), 2)
        ];
        
        $this->results['api_response_times'] = $this->endpoints;
        
        // Assert all endpoints have average response time < 500ms
        foreach ($this->endpoints as $endpoint => $metrics) {
            $this->assertLessThan(500, $metrics['avg_ms'], 
                "Endpoint {$endpoint} average response time exceeds 500ms");
        }
    }

    public function test_api_throughput()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $requestsPerSecond = [];
        
        for ($i = 0; $i < 5; $i++) {
            $start = microtime(true);
            $count = 0;
            
            while ((microtime(true) - $start) < 1) {
                $this->withHeader('Authorization', 'Bearer ' . $token)
                    ->getJson('/api/v1/dashboard/stats');
                $count++;
            }
            
            $requestsPerSecond[] = $count;
        }
        
        $avgThroughput = array_sum($requestsPerSecond) / count($requestsPerSecond);
        
        $this->results['api_throughput'] = [
            'avg_requests_per_second' => round($avgThroughput, 2),
            'samples' => $requestsPerSecond
        ];
        
        $this->assertGreaterThan(20, $avgThroughput, 'API throughput too low');
    }

    public function test_api_payload_size()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $endpoints = [
            '/api/v1/servers',
            '/api/v1/criteria',
            '/api/v1/dashboard/stats'
        ];
        
        $sizes = [];
        
        foreach ($endpoints as $endpoint) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson($endpoint);
            
            $content = $response->getContent();
            $size = strlen($content);
            $sizes[$endpoint] = round($size / 1024, 2);
        }
        
        $this->results['api_payload_sizes_kb'] = $sizes;
        
        foreach ($sizes as $endpoint => $size) {
            $this->assertLessThan(500, $size, "Payload size for {$endpoint} exceeds 500KB");
        }
    }

    public function test_api_concurrent_performance()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $concurrent = 20;
        $startTime = microtime(true);
        $successCount = 0;
        
        for ($i = 0; $i < $concurrent; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/v1/dashboard/stats');
            
            if ($response->status() === 200) {
                $successCount++;
            }
        }
        
        $duration = (microtime(true) - $startTime) * 1000;
        
        $this->results['api_concurrent_performance'] = [
            'concurrent_requests' => $concurrent,
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'avg_time_per_request' => round($duration / $concurrent, 2)
        ];
        
        $this->assertEquals($concurrent, $successCount, 'Not all concurrent requests succeeded');
    }

    protected function percentile($array, $percentile)
    {
        sort($array);
        $index = ($percentile / 100) * (count($array) - 1);
        if (floor($index) == $index) {
            return $array[$index];
        }
        $floor = floor($index);
        $ceil = ceil($index);
        return $array[$floor] + ($array[$ceil] - $array[$floor]) * ($index - $floor);
    }
}
