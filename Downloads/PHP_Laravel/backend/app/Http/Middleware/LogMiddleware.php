<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        Log::channel('api')->info('Request received', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'params' => $request->except(['password', 'token'])
        ]);

        $response = $next($request);
        
        $duration = round((microtime(true) - $start) * 1000, 2);
        
        Log::channel('api')->info('Response sent', [
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'url' => $request->fullUrl()
        ]);

        return $response;
    }
}