<?php

namespace App\Http\Middleware;

use Closure;

class JsonMiddleware
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            if ($request->headers->get('Content-Type') !== 'application/json') {
                $request->headers->set('Content-Type', 'application/json');
            }
        }

        $response = $next($request);
        
        if (!$response->headers->get('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}