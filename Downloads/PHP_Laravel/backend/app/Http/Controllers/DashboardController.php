<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function stats()
    {
        $stats = $this->dashboard->getStats();
        return $this->success($stats, 'Dashboard stats');
    }

    public function charts()
    {
        $charts = $this->dashboard->getChartData();
        return $this->success($charts, 'Chart data');
    }

    public function recent()
    {
        $recent = $this->dashboard->getRecentActivities();
        return $this->success($recent, 'Recent activities');
    }

    public function trends()
    {
        $trends = $this->dashboard->getTrends();
        return $this->success($trends, 'Trend data');
    }

    public function compliance()
    {
        $compliance = $this->dashboard->getComplianceStats();
        return $this->success($compliance, 'Compliance statistics');
    }
}