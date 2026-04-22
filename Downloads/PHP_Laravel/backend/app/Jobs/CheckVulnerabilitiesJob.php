<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\ApiCallService;
use App\Services\VulnerabilityService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class CheckVulnerabilitiesJob extends BaseJob
{
    public function handle(ApiCallService $api, VulnerabilityService $vulnService, NotificationService $notification)
    {
        $servers = Server::where('status', 'active')->get();
        $newVulnerabilities = 0;
        
        foreach ($servers as $server) {
            // Check for new CVEs related to server OS/packages
            $cves = $api->searchCVE($server->os_type, 20);
            
            foreach ($cves as $cve) {
                $existing = $vulnService->findByCVE($cve['id']);
                
                if (!$existing) {
                    $vulnService->createFromCVE($server->id, $cve);
                    $newVulnerabilities++;
                    
                    // Notify admins
                    $notification->sendPush(1, 'New Vulnerability', "New CVE {$cve['id']} detected on {$server->name}");
                }
            }
        }
        
        Log::info('Vulnerability check completed', ['new_vulnerabilities' => $newVulnerabilities]);
    }
}