<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\ScannerService;
use App\Services\RealtimeService;
use Illuminate\Support\Facades\Log;

class ScanServerJob extends BaseJob
{
    protected $server;
    protected $realtime;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function handle(ScannerService $scanner, RealtimeService $realtime)
    {
        $this->realtime = $realtime;
        
        try {
            $this->realtime->broadcastScanProgress($this->server->id, 0, 'Starting scan...');
            
            $result = $scanner->scan($this->server);
            
            $this->realtime->broadcastScanProgress($this->server->id, 100, 'Scan completed');
            
            Log::info('Server scan completed', [
                'server_id' => $this->server->id,
                'vulnerabilities_found' => count($result['vulnerabilities'] ?? [])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Scan failed', [
                'server_id' => $this->server->id,
                'error' => $e->getMessage()
            ]);
            
            $this->realtime->broadcastScanProgress($this->server->id, -1, 'Scan failed: ' . $e->getMessage());
            
            throw $e;
        }
    }
}