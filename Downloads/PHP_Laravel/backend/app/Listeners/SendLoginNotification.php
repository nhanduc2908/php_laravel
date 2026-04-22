<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\NotificationService;

class SendLoginNotification extends BaseListener
{
    protected $notification;

    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }

    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        // Send email notification
        $this->notification->sendEmail(
            $user->email,
            'New Login to Your Account',
            'emails.auth.login_alert',
            [
                'name' => $user->name,
                'ip' => $ip,
                'user_agent' => $userAgent,
                'time' => now()->toDateTimeString()
            ]
        );

        // Send push notification
        $this->notification->sendPush(
            $user->id,
            'New Login',
            "A new login was detected from IP: {$ip}"
        );
    }
}