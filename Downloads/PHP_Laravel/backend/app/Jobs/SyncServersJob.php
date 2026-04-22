<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\ApiCallService;
use Illuminate\Support\Facades\Log;

class SyncServersJob extends BaseJob
{
    protected $source;

    public function __construct($source = 'api')
    {
        $this->source = $source;
    }

    public function handle(ApiCallService $api)
    {
        // Example: sync from external API
        $externalServers = $api->makeRequest('GET', config('services.server_sync.url'));
        
        if (!$externalServers) {
            Log::warning('External server sync failed');
            return;
        }
        
        $synced = 0;
        $updated = 0;
        
        foreach ($externalServers as $external) {
            $server = Server::updateOrCreate(
                ['host' => $external['host']],
                [
                    'name' => $external['name'],
                    'port' => $external['port'],
                    'username' => $external['username'],
                    'status' => $external['status'] ?? 'active'
                ]
            );
            
            if ($server->wasRecentlyCreated) {
                $synced++;
            } else {
                $updated++;
            }
        }
        
        Log::info('Server sync completed', [
            'source' => $this->source,
            'new' => $synced,
            'updated' => $updated
        ]);
    }
}