<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class CsrfMiddleware extends BaseVerifier
{
    protected $except = [
        'api/*'
    ];

    public function handle($request, Closure $next)
    {
        if ($this->isReading($request) || $this->inExceptArray($request) || $this->tokensMatch($request)) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'code' => 419,
            'message' => 'CSRF token mismatch'
        ], 419);
    }
}