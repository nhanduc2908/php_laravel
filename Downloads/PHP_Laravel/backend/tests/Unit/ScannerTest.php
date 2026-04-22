<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Server;
use App\Services\ScannerService;
use App\Services\SshConnectionService;
use Mockery;

class ScannerTest extends TestCase
{
    protected $scanner;
    protected $sshMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sshMock = Mockery::mock(SshConnectionService::class);
        $this->scanner = new ScannerService($this->sshMock);
    }

    public function test_scan_server_successfully()
    {
        $server = Server::factory()->create();
        
        $this->sshMock->shouldReceive('connect')
                      ->once()
                      ->andReturn(true);
        
        $this->sshMock->shouldReceive('exec')
                      ->with(Mockery::any(), Mockery::any())
                      ->andReturn('Ubuntu 22.04');
        
        $result = $this->scanner->scan($server);
        
        $this->assertArrayHasKey('os', $result);
        $this->assertArrayHasKey('packages', $result);
        $this->assertArrayHasKey('vulnerabilities', $result);
    }

    public function test_scan_handles_connection_failure()
    {
        $this->expectException(\Exception::class);
        
        $server = Server::factory()->create();
        
        $this->sshMock->shouldReceive('connect')
                      ->once()
                      ->andThrow(new \Exception('Connection failed'));
        
        $this->scanner->scan($server);
    }

    public function test_vulnerability_detection()
    {
        $server = Server::factory()->create();
        
        $this->sshMock->shouldReceive('connect')->andReturn(true);
        $this->sshMock->shouldReceive('exec')->andReturn('PermitRootLogin yes');
        
        $result = $this->scanner->scan($server);
        
        $this->assertNotEmpty($result['vulnerabilities']);
    }
}