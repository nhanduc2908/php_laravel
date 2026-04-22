<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function index(Request $request)
    {
        $query = Alert::query();
        
        if ($request->severity) {
            $query->where('severity', $request->severity);
        }
        
        if ($request->read === 'false') {
            $query->where('is_read', false);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);
        return $this->success($alerts, 'Alerts retrieved');
    }

    public function show($id)
    {
        $alert = Alert::findOrFail($id);
        return $this->success($alert, 'Alert retrieved');
    }

    public function markRead($id)
    {
        $alert = $this->alertService->markAsRead($id);
        return $this->success($alert, 'Alert marked as read');
    }

    public function bulkRead(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $count = $this->alertService->bulkMarkAsRead($request->ids);
        return $this->success(['count' => $count], 'Alerts marked as read');
    }

    public function resolve($id)
    {
        $alert = $this->alertService->resolve($id);
        return $this->success($alert, 'Alert resolved');
    }

    public function ignore($id)
    {
        $alert = $this->alertService->ignore($id);
        return $this->success($alert, 'Alert ignored');
    }

    public function cleanOld()
    {
        $count = $this->alertService->cleanOldAlerts();
        return $this->success(['deleted' => $count], 'Old alerts cleaned');
    }
}