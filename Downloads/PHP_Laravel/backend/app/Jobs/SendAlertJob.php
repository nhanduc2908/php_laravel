<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Services\NotificationService;
use App\Services\RealtimeService;
use Illuminate\Support\Facades\Log;

class SendAlertJob extends BaseJob
{
    protected $alert;

    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    public function handle(NotificationService $notification, RealtimeService $realtime)
    {
        // Send realtime to all admins
        $realtime->pushToAdmin('alert.new', $this->alert);
        
        // Send email to super admins
        $admins = \App\Models\User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['super_admin', 'admin']);
        })->get();
        
        foreach ($admins as $admin) {
            $notification->sendEmail($admin->email, 'Security Alert', 'emails.alert', [
                'alert' => $this->alert,
                'user' => $admin
            ]);
        }
        
        Log::info('Alert sent', ['alert_id' => $this->alert->id, 'severity' => $this->alert->severity]);
    }
}