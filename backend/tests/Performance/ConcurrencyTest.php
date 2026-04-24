<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ConcurrencyTest extends TestCase
{
    protected $concurrentUsers = 20;
    protected $results = [];

    protected function tearDown(): void
    {
        Log::channel('performance')->info('Concurrency test completed', $this->results);
        parent::tearDown();
    }

    public function test_concurrent_login_attempts()
    {
        $users = User::factory()->count($this->concurrentUsers)->create();
        $startTime = microtime(true);
        $successCount = 0;
        $responses = [];

        // Simulate concurrent requests using parallel testing
        $promises = [];
        foreach ($users as $user) {
            $promises[] = $this->postJsonAsync('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'password'
            ]);
        }

        foreach ($promises as $promise) {
            $response = $promise->wait();
            $responses[] = $response;
            if ($response->status() === 200) {
                $successCount++;
            }
        }

        $duration = (microtime(true) - $startTime) * 1000;

        $this->results['concurrent_login'] = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'total_attempts' => count($users),
            'success_rate' => round(($successCount / count($users)) * 100, 2)
        ];

        $this->assertEquals($this->concurrentUsers, $successCount, 'Not all concurrent logins succeeded');
    }

    public function test_concurrent_assessment_runs()
    {
        $servers = Server::factory()->count(10)->create();
        $criteria = Criteria::factory()->count(20)->create();
        $user = User::factory()->create();
        $token = auth()->login($user);
        
        $startTime = microtime(true);
        $successCount = 0;
        $responses = [];

        foreach ($servers as $server) {
            $answers = [];
            foreach ($criteria as $c) {
                $answers[] = [
                    'criteria_id' => $c->id,
                    'value' => 'yes',
                    'evidence' => 'Test evidence'
                ];
            }

            $responses[] = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->postJson('/api/v1/assessments/run', [
                    'server_id' => $server->id,
                    'answers' => $answers
                ]);
        }

        foreach ($responses as $response) {
            if ($response->status() === 200) {
                $successCount++;
            }
        }

        $duration = (microtime(true) - $startTime) * 1000;

        $this->results['concurrent_assessments'] = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'total_assessments' => count($servers),
            'avg_time_per_assessment' => round($duration / count($servers), 2)
        ];

        $this->assertEquals(count($servers), $successCount, 'Not all concurrent assessments succeeded');
    }

    public function test_concurrent_file_uploads()
    {
        $server = Server::factory()->create();
        $user = User::factory()->create();
        $token = auth()->login($user);
        $files = [];
        
        for ($i = 0; $i < 10; $i++) {
            $files[] = UploadedFile::fake()->create("test_{$i}.pdf", 100);
        }
        
        $startTime = microtime(true);
        $successCount = 0;

        foreach ($files as $i => $file) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->postJson('/api/v1/assessment-files', [
                    'title' => "Concurrent File {$i}",
                    'content' => 'Test content',
                    'server_id' => $server->id,
                    'attachment' => $file
                ]);
            
            if ($response->status() === 201) {
                $successCount++;
            }
        }
        
        $duration = (microtime(true) - $startTime) * 1000;

        $this->results['concurrent_uploads'] = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'total_uploads' => count($files),
            'success_rate' => round(($successCount / count($files)) * 100, 2)
        ];

        $this->assertEquals(count($files), $successCount, 'Not all concurrent file uploads succeeded');
    }

    public function test_concurrent_api_requests()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);
        $totalRequests = 50;
        $successCount = 0;
        
        $startTime = microtime(true);

        for ($i = 0; $i < $totalRequests; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/v1/dashboard/stats');
            
            if ($response->status() === 200) {
                $successCount++;
            }
        }

        $duration = (microtime(true) - $startTime) * 1000;
        $throughput = ($totalRequests / ($duration / 1000));

        $this->results['concurrent_api'] = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'total_requests' => $totalRequests,
            'throughput_rps' => round($throughput, 2),
            'success_rate' => round(($successCount / $totalRequests) * 100, 2)
        ];

        $this->assertGreaterThan(0.95, $this->results['concurrent_api']['success_rate'] / 100, 
            'Success rate below 95% under concurrent requests');
    }

    public function test_concurrent_database_transactions()
    {
        $startTime = microtime(true);
        $successCount = 0;
        $totalTransactions = 20;

        for ($i = 0; $i < $totalTransactions; $i++) {
            try {
                DB::beginTransaction();
                
                $user = User::factory()->create();
                $server = Server::factory()->create();
                $criteria = Criteria::factory()->create();
                
                DB::commit();
                $successCount++;
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

        $duration = (microtime(true) - $startTime) * 1000;

        $this->results['concurrent_transactions'] = [
            'duration_ms' => round($duration, 2),
            'success_count' => $successCount,
            'total_transactions' => $totalTransactions,
            'avg_time_per_transaction' => round($duration / $totalTransactions, 2)
        ];

        $this->assertEquals($totalTransactions, $successCount, 'Not all concurrent transactions succeeded');
    }
}