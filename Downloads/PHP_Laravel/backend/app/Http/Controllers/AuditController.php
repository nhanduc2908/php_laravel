<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user');
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }
        
        if ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(30);
        return $this->success($logs, 'Audit logs retrieved');
    }

    public function show($id)
    {
        $log = AuditLog::with('user')->findOrFail($id);
        return $this->success($log, 'Audit log detail');
    }

    public function userLogs($userId)
    {
        $logs = AuditLog::where('user_id', $userId)->paginate(30);
        return $this->success($logs, 'User audit logs');
    }

    public function export(Request $request)
    {
        $logs = AuditLog::filter($request->all())->get();
        return response()->json($logs);
    }
}