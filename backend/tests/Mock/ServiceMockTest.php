<?php

namespace Tests\Mock;

use Tests\TestCase;
use App\Services\ScannerService;
use App\Services\ScoreCalculatorService;
use App\Services\NotificationService;
use App\Models\Server;
use Mockery;

class ServiceMockTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_scanner_service_mock_returns_expected_result()
    {
        $mockScanner = Mockery::mock(ScannerService::class);
        $server = Server::factory()->make(['id' => 1]);
        
        $expectedResult = [
            'os' => 'Ubuntu 22.04',
            'vulnerabilities' => [['name' => 'Test vuln', 'severity' => 'high']],
            'openPorts' => [22, 80, 443]
        ];
        
        $mockScanner->shouldReceive('scan')
            ->once()
            ->with($server)
            ->andReturn($expectedResult);
        
        $result = $mockScanner->scan($server);
        
        $this->assertEquals($expectedResult, $result);
        $this->assertArrayHasKey('os', $result);
        $this->assertArrayHasKey('vulnerabilities', $result);
    }

    public function test_score_calculator_service_mock()
    {
        $mockCalculator = Mockery::mock(ScoreCalculatorService::class);
        $server = Server::factory()->make(['id' => 1]);
        
        $expectedScore = [
            'total_score' => 85.5,
            'grade' => 'B',
            'compliance_rate' => 75.0
        ];
        
        $mockCalculator->shouldReceive('calculateScore')
            ->once()
            ->with($server)
            ->andReturn($expectedScore);
        
        $result = $mockCalculator->calculateScore($server);
        
        $this->assertEquals(85.5, $result['total_score']);
        $this->assertEquals('B', $result['grade']);
    }

    public function test_notification_service_mock_verify_calls()
    {
        $mockNotification = Mockery::mock(NotificationService::class);
        
        $mockNotification->shouldReceive('sendEmail')
            ->once()
            ->with('test@example.com', 'Test Subject', Mockery::any(), Mockery::any())
            ->andReturn(true);
        
        $mockNotification->shouldReceive('sendPush')
            ->once()
            ->with(1, 'Test Title', 'Test Body', Mockery::any())
            ->andReturn(true);
        
        $emailResult = $mockNotification->sendEmail('test@example.com', 'Test Subject', 'emails.test', []);
        $pushResult = $mockNotification->sendPush(1, 'Test Title', 'Test Body', []);
        
        $this->assertTrue($emailResult);
        $this->assertTrue($pushResult);
    }

    public function test_service_mock_with_exception()
    {
        $mockScanner = Mockery::mock(ScannerService::class);
        $server = Server::factory()->make();
        
        $mockScanner->shouldReceive('scan')
            ->once()
            ->with($server)
            ->andThrow(new \Exception('SSH connection failed'));
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('SSH connection failed');
        
        $mockScanner->scan($server);
    }

    public function test_service_mock_with_multiple_calls()
    {
        $mockCalculator = Mockery::mock(ScoreCalculatorService::class);
        $server1 = Server::factory()->make(['id' => 1]);
        $server2 = Server::factory()->make(['id' => 2]);
        
        $mockCalculator->shouldReceive('calculateScore')
            ->times(2)
            ->andReturn(
                ['total_score' => 90.0, 'grade' => 'A'],
                ['total_score' => 65.0, 'grade' => 'D']
            );
        
        $result1 = $mockCalculator->calculateScore($server1);
        $result2 = $mockCalculator->calculateScore($server2);
        
        $this->assertEquals(90.0, $result1['total_score']);
        $this->assertEquals(65.0, $result2['total_score']);
    }
}