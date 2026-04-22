<?php

namespace App\Http\Middleware;

use Closure;
use App\Wrappers\ApiWrapper;
use App\Wrappers\ErrorWrapper;

class WrapperMiddleware
{
    protected $apiWrapper;
    protected $errorWrapper;

    public function __construct(ApiWrapper $apiWrapper, ErrorWrapper $errorWrapper)
    {
        $this->apiWrapper = $apiWrapper;
        $this->errorWrapper = $errorWrapper;
    }

    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
            
            if ($response->getStatusCode() === 200 && $response->getData()) {
                $wrapped = $this->apiWrapper->wrap($response->getData(true));
                return response()->json($wrapped, $response->getStatusCode());
            }
            
            return $response;
            
        } catch (\Exception $e) {
            $error = $this->errorWrapper->wrap($e);
            return response()->json($error, $error['code'] ?? 500);
        }
    }
}