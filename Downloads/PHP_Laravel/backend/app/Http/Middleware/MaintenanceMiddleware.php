<?php

namespace App\Http\Middleware;

use Closure;

class MaintenanceMiddleware
{
    public function handle($request, Closure $next)
    {
        if (config('app.maintenance_mode', false)) {
            $allowedIps = config('app.maintenance_allowed_ips', []);
            
            if (!in_array($request->ip(), $allowedIps)) {
                return response()->json([
                    'status' => 'error',
                    'code' => 503,
                    'message' => 'System is under maintenance. Please try again later.'
                ], 503);
            }
        }

        return $next($request);
    }
}