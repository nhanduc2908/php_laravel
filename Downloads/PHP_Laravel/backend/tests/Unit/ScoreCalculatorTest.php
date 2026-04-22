<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use App\Services\ScoreCalculatorService;

class ScoreCalculatorTest extends TestCase
{
    protected $calculator;
    protected $server;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new ScoreCalculatorService();
        $this->server = Server::factory()->create();
    }

    public function test_calculate_total_score()
    {
        $criteria1 = Criteria::factory()->create(['weight' => 10]);
        $criteria2 = Criteria::factory()->create(['weight' => 20]);
        
        AssessmentResult::create([
            'server_id' => $this->server->id,
            'criteria_id' => $criteria1->id,
            'score' => 8
        ]);
        
        AssessmentResult::create([
            'server_id' => $this->server->id,
            'criteria_id' => $criteria2->id,
            'score' => 15
        ]);
        
        $result = $this->calculator->calculateScore($this->server);
        
        $expectedScore = (8 + 15) / 30 * 100;
        $this->assertEquals(round($expectedScore, 2), $result['total_score']);
    }

    public function test_calculate_compliance_rate()
    {
        $criteria1 = Criteria::factory()->create();
        $criteria2 = Criteria::factory()->create();
        $criteria3 = Criteria::factory()->create();
        
        AssessmentResult::create([
            'server_id' => $this->server->id,
            'criteria_id' => $criteria1->id,
            'score' => 5
        ]);
        
        AssessmentResult::create([
            'server_id' => $this->server->id,
            'criteria_id' => $criteria2->id,
            'score' => 0
        ]);
        
        $result = $this->calculator->calculateCompliance($this->server);
        
        $expectedRate = (1 / 3) * 100;
        $this->assertEquals(round($expectedRate, 2), $result['compliance_rate']);
    }

    public function test_calculate_grade()
    {
        $result = $this->calculator->calculateScore($this->server);
        
        $this->assertArrayHasKey('grade', $result);
        $this->assertContains($result['grade'], ['A', 'B', 'C', 'D', 'F']);
    }
}