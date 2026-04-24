<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'User not found'
                ], 401);
            }
            
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Token has expired'
            ], 401);
            
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Token is invalid'
            ], 401);
            
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Token not provided'
            ], 401);
        }

        return $next($request);
    }
}