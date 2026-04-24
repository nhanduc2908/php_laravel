<?php

namespace App\Listeners;

use App\Events\AssessmentCompleted;
use App\Events\VulnerabilityDetected;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Cache;

class UpdateDashboardCache extends BaseListener
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function handleAssessmentCompleted(AssessmentCompleted $event)
    {
        $this->clearCache();
    }

    public function handleVulnerabilityDetected(VulnerabilityDetected $event)
    {
        $this->clearCache();
    }

    protected function clearCache()
    {
        $keys = [
            'dashboard_stats',
            'dashboard_charts',
            'dashboard_recent',
            'dashboard_trends',
            'dashboard_compliance'
        ];
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        
        // Also clear user-specific dashboard caches
        Cache::tags(['dashboard'])->flush();
    }

    public function subscribe($events)
    {
        $events->listen(
            AssessmentCompleted::class,
            [UpdateDashboardCache::class, 'handleAssessmentCompleted']
        );
        $events->listen(
            VulnerabilityDetected::class,
            [UpdateDashboardCache::class, 'handleVulnerabilityDetected']
        );
    }
}