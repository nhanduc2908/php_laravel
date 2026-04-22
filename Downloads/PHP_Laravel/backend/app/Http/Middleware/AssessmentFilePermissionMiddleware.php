<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AssessmentFile;
use App\Services\RBACService;

class AssessmentFilePermissionMiddleware
{
    protected $rbac;

    public function __construct(RBACService $rbac)
    {
        $this->rbac = $rbac;
    }

    public function handle($request, Closure $next, $action)
    {
        $user = auth()->user();
        $fileId = $request->route('id');
        
        if (!$fileId) {
            return $next($request);
        }

        $file = AssessmentFile::find($fileId);
        
        if (!$file) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'File not found'
            ], 404);
        }

        $canAccess = false;

        switch ($action) {
            case 'view':
                $canAccess = ($file->created_by == $user->id) || 
                             $this->rbac->hasPermission($user, 'view_all_files') ||
                             $file->isSharedWith($user->id);
                break;
            case 'edit':
                $canAccess = ($file->created_by == $user->id) || 
                             $this->rbac->hasPermission($user, 'edit_all_files');
                break;
            case 'delete':
                $canAccess = $this->rbac->hasPermission($user, 'delete_any_file');
                break;
            case 'share':
                $canAccess = ($file->created_by == $user->id) || 
                             $this->rbac->hasPermission($user, 'share_any_file');
                break;
        }

        if (!$canAccess) {
            return response()->json([
                'status' => 'error',
                'code' => 403,
                'message' => "You don't have permission to {$action} this file"
            ], 403);
        }

        return $next($request);
    }
}