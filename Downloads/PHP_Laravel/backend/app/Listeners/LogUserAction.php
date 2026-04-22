<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use App\Models\AuditLog;

class LogUserAction extends BaseListener
{
    public function handleLogin(Login $event)
    {
        $this->log($event->user->id, 'login', 'user', $event->user->id, [
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function handleLogout(Logout $event)
    {
        $userId = $event->user?->id ?? auth()->id();
        if ($userId) {
            $this->log($userId, 'logout', 'user', $userId);
        }
    }

    public function handleRegister(Registered $event)
    {
        $this->log($event->user->id, 'register', 'user', $event->user->id);
    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [LogUserAction::class, 'handleLogin']
        );
        $events->listen(
            Logout::class,
            [LogUserAction::class, 'handleLogout']
        );
        $events->listen(
            Registered::class,
            [LogUserAction::class, 'handleRegister']
        );
    }

    protected function log($userId, $action, $resource, $resourceId, $extra = [])
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'resource' => $resource,
            'resource_id' => $resourceId,
            'old_values' => null,
            'new_values' => $extra,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}