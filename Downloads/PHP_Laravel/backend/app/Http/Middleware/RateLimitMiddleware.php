<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestKey($request);
        
        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);
            
            return response()->json([
                'status' => 'error',
                'code' => 429,
                'message' => 'Too many requests',
                'retry_after' => $retryAfter
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response->header('X-RateLimit-Limit', $maxAttempts)
                        ->header('X-RateLimit-Remaining', $maxAttempts - $this->limiter->attempts($key));
    }

    protected function resolveRequestKey($request)
    {
        return $request->ip() . ':' . $request->user()?->id;
    }
}