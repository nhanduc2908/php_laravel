<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoadTest extends TestCase
{
    protected $startTime;
    protected $endTime;
    protected $results = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->startTime = microtime(true);
        DB::enableQueryLog();
    }

    protected function tearDown(): void
    {
        $this->endTime = microtime(true);
        $duration = ($this->endTime - $this->startTime) * 1000;
        
        Log::channel('performance')->info('Load test completed', [
            'duration_ms' => round($duration, 2),
            'queries' => count(DB::getQueryLog()),
            'results' => $this->results
        ]);
        
        DB::disableQueryLog();
        parent::tearDown();
    }

    public function test_concurrent_user_login()
    {
        $users = User::factory()->count(100)->create();
        $loginTimes = [];
        
        foreach ($users as $user) {
            $start = microtime(true);
            
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'password'
            ]);
            
            $duration = (microtime(true) - $start) * 1000;
            $loginTimes[] = $duration;
            
            $response->assertStatus(200);
        }
        
        $avgTime = array_sum($loginTimes) / count($loginTimes);
        $maxTime = max($loginTimes);
        $minTime = min($loginTimes);
        
        $this->results['login'] = [
            'avg_ms' => round($avgTime, 2),
            'max_ms' => round($maxTime, 2),
            'min_ms' => round($minTime, 2),
            'total' => count($loginTimes)
        ];
        
        $this->assertLessThan(500, $avgTime, 'Average login time exceeds 500ms');
    }

    public function test_bulk_server_creation()
    {
        $admin = $this->actingAsAdmin();
        $createTimes = [];
        
        for ($i = 0; $i < 50; $i++) {
            $start = microtime(true);
            
            $response = $this->postJson('/api/v1/servers', [
                'name' => "Load Test Server {$i}",
                'host' => "192.168.1.{$i}",
                'port' => 22,
                'username' => 'admin',
                'status' => 'active'
            ]);
            
            $duration = (microtime(true) - $start) * 1000;
            $createTimes[] = $duration;
            
            $response->assertStatus(201);
        }
        
        $avgTime = array_sum($createTimes) / count($createTimes);
        
        $this->results['server_creation'] = [
            'avg_ms' => round($avgTime, 2),
            'total_created' => count($createTimes)
        ];
        
        $this->assertLessThan(300, $avgTime, 'Average server creation time exceeds 300ms');
    }

    public function test_bulk_criteria_retrieval()
    {
        \App\Models\Criteria::factory()->count(280)->create();
        
        $start = microtime(true);
        
        $response = $this->getJson('/api/v1/criteria?per_page=100');
        
        $duration = (microtime(true) - $start) * 1000;
        
        $response->assertStatus(200);
        
        $this->results['criteria_retrieval'] = [
            'duration_ms' => round($duration, 2),
            'total_criteria' => 280
        ];
        
        $this->assertLessThan(1000, $duration, 'Criteria retrieval exceeds 1 second');
    }

    public function test_bulk_assessment_run()
    {
        $servers = Server::factory()->count(20)->create();
        $criteria = \App\Models\Criteria::factory()->count(50)->create();
        
        $runTimes = [];
        
        foreach ($servers as $server) {
            $answers = [];
            foreach ($criteria as $c) {
                $answers[] = [
                    'criteria_id' => $c->id,
                    'value' => 'yes',
                    'evidence' => 'Test evidence'
                ];
            }
            
            $start = microtime(true);
            
            $response = $this->postJson('/api/v1/assessments/run', [
                'server_id' => $server->id,
                'answers' => $answers
            ]);
            
            $duration = (microtime(true) - $start) * 1000;
            $runTimes[] = $duration;
            
            $response->assertStatus(200);
        }
        
        $avgTime = array_sum($runTimes) / count($runTimes);
        
        $this->results['assessment_run'] = [
            'avg_ms' => round($avgTime, 2),
            'total_assessments' => count($runTimes)
        ];
        
        $this->assertLessThan(2000, $avgTime, 'Average assessment time exceeds 2 seconds');
    }
}