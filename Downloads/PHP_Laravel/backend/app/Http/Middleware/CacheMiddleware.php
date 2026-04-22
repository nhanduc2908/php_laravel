<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheMiddleware
{
    public function handle($request, Closure $next, $ttl = 3600)
    {
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        $key = 'cache_' . md5($request->fullUrl() . auth()->id());
        
        if (Cache::has($key)) {
            return response()->json(Cache::get($key));
        }

        $response = $next($request);
        
        if ($response->getStatusCode() === 200 && $response->getData(true)) {
            Cache::put($key, $response->getData(true), $ttl);
        }

        return $response;
    }
}