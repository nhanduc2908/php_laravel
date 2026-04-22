<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabasePerformanceTest extends TestCase
{
    protected $queries = [];
    protected $slowQueryThreshold = 100;
    protected $results = [];

    protected function setUp(): void
    {
        parent::setUp();
        DB::enableQueryLog();
    }

    protected function tearDown(): void
    {
        $this->analyzeQueryPerformance();
        DB::disableQueryLog();
        Log::channel('performance')->info('Database performance test completed', $this->results);
        parent::tearDown();
    }

    public function test_index_performance()
    {
        User::factory()->count(10000)->create();
        Server::factory()->count(5000)->create();
        
        // Indexed query
        $start = microtime(true);
        $user = User::where('email', User::first()->email)->first();
        $indexedTime = (microtime(true) - $start) * 1000;
        
        // Non-indexed query
        $start = microtime(true);
        $users = User::where('name', 'like', '%test%')->take(100)->get();
        $nonIndexedTime = (microtime(true) - $start) * 1000;
        
        $this->results['index_performance'] = [
            'indexed_query_ms' => round($indexedTime, 2),
            'non_indexed_query_ms' => round($nonIndexedTime, 2),
            'improvement_factor' => round($nonIndexedTime / max($indexedTime, 0.01), 2)
        ];
        
        $this->assertLessThan(50, $indexedTime, 'Indexed query too slow');
    }

    public function test_join_performance()
    {
        $servers = Server::factory()->count(1000)->create();
        foreach ($servers as $server) {
            AssessmentResult::factory()->count(5)->create(['server_id' => $server->id]);
        }
        
        $start = microtime(true);
        $results = DB::table('servers')
            ->leftJoin('assessment_results', 'servers.id', '=', 'assessment_results.server_id')
            ->select('servers.*', DB::raw('COUNT(assessment_results.id) as assessment_count'))
            ->groupBy('servers.id')
            ->get();
        $joinTime = (microtime(true) - $start) * 1000;
        
        $this->results['join_performance'] = [
            'duration_ms' => round($joinTime, 2),
            'records_returned' => $results->count()
        ];
        
        $this->assertLessThan(500, $joinTime, 'Join query too slow');
    }

    public function test_bulk_insert_performance()
    {
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'name' => "Bulk Test {$i}",
                'host' => "192.168.1.{$i}",
                'port' => 22,
                'username' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        $start = microtime(true);
        DB::table('servers')->insert($data);
        $bulkTime = (microtime(true) - $start) * 1000;
        
        $this->results['bulk_insert'] = [
            'bulk_insert_ms' => round($bulkTime, 2),
            'records_inserted' => 1000,
            'records_per_second' => round(1000 / ($bulkTime / 1000), 2)
        ];
        
        $this->assertLessThan(1000, $bulkTime, 'Bulk insert too slow');
    }

    public function test_pagination_performance()
    {
        Criteria::factory()->count(5000)->create();
        
        $pageSizes = [10, 25, 50, 100, 200];
        $results = [];
        
        foreach ($pageSizes as $size) {
            $start = microtime(true);
            $criteria = Criteria::paginate($size);
            $time = (microtime(true) - $start) * 1000;
            $results[$size] = round($time, 2);
        }
        
        $this->results['pagination_performance'] = $results;
        
        foreach ($results as $size => $time) {
            $this->assertLessThan(200, $time, "Pagination with size {$size} too slow");
        }
    }

    public function test_aggregation_performance()
    {
        AssessmentResult::factory()->count(5000)->create();
        
        $start = microtime(true);
        $avgScore = AssessmentResult::avg('score');
        $avgTime = (microtime(true) - $start) * 1000;
        
        $start = microtime(true);
        $grouped = AssessmentResult::select('server_id', DB::raw('AVG(score) as avg_score'))
            ->groupBy('server_id')
            ->get();
        $groupTime = (microtime(true) - $start) * 1000;
        
        $this->results['aggregation_performance'] = [
            'avg_calculation_ms' => round($avgTime, 2),
            'grouped_aggregation_ms' => round($groupTime, 2),
            'records_processed' => 5000
        ];
        
        $this->assertLessThan(200, $avgTime, 'Average calculation too slow');
        $this->assertLessThan(500, $groupTime, 'Grouped aggregation too slow');
    }

    protected function analyzeQueryPerformance()
    {
        $queries = DB::getQueryLog();
        $slowQueries = [];
        
        foreach ($queries as $query) {
            if ($query['time'] > $this->slowQueryThreshold) {
                $slowQueries[] = [
                    'sql' => $query['query'],
                    'time_ms' => $query['time'],
                    'bindings' => $query['bindings']
                ];
            }
        }
        
        $this->results['slow_queries'] = [
            'count' => count($slowQueries),
            'threshold_ms' => $this->slowQueryThreshold,
            'total_queries' => count($queries)
        ];
        
        if (!empty($slowQueries)) {
            Log::channel('performance')->warning('Slow queries detected', ['queries' => $slowQueries]);
        }
    }
}