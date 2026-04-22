<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Auth events
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\SendLoginNotification',
            'App\Listeners\LogUserAction',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogUserAction',
        ],
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\SendWelcomeEmail',
        ],
        
        // Assessment events
        'App\Events\AssessmentCompleted' => [
            'App\Listeners\SendAssessmentNotification',
            'App\Listeners\UpdateDashboardCache',
            'App\Listeners\GenerateReport',
        ],
        
        // Vulnerability events
        'App\Events\VulnerabilityDetected' => [
            'App\Listeners\SendAlertOnVulnerability',
            'App\Listeners\PushRealtimeEvent',
            'App\Listeners\CreateAuditTrail',
        ],
        
        // File events
        'App\Events\FileShared' => [
            'App\Listeners\NotifyFileShared',
            'App\Listeners\PushRealtimeEvent',
        ],
        
        // System events
        'App\Events\BackupCompleted' => [
            'App\Listeners\SendBackupNotification',
            'App\Listeners\CleanOldBackups',
        ],
    ];

    protected $subscribe = [
        'App\Listeners\LanguageListener',
        'App\Listeners\AuditTrailListener',
    ];

    public function boot(): void
    {
        parent::boot();
    }
}