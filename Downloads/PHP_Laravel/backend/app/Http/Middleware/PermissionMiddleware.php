<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\RBACService;

class PermissionMiddleware
{
    protected $rbac;

    public function __construct(RBACService $rbac)
    {
        $this->rbac = $rbac;
    }

    public function handle($request, Closure $next, $permission)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Unauthenticated'
            ], 401);
        }

        if (!$this->rbac->hasPermission($user, $permission)) {
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => "You don't have permission: {$permission}"
            ], 403);
        }

        return $next($request);
    }
}