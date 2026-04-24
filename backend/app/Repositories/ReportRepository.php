<?php

namespace App\Repositories;

use App\Models\AssessmentReport;

class ReportRepository extends BaseRepository
{
    public function model()
    {
        return AssessmentReport::class;
    }

    public function getByUser($userId, $perPage = 15)
    {
        return $this->model->where('created_by', $userId)
            ->with('server')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getByDate($startDate, $endDate, $perPage = 15)
    {
        return $this->model->whereBetween('created_at', [$startDate, $endDate])
            ->with('server')
            ->paginate($perPage);
    }

    public function getByServer($serverId, $perPage = 15)
    {
        return $this->model->where('server_id', $serverId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getLatestReport($serverId)
    {
        return $this->model->where('server_id', $serverId)
            ->latest()
            ->first();
    }

    public function getReportStats($serverId = null)
    {
        $query = $this->model;
        
        if ($serverId) {
            $query->where('server_id', $serverId);
        }
        
        return [
            'total_reports' => $query->count(),
            'average_score' => $query->avg('total_score'),
            'highest_score' => $query->max('total_score'),
            'lowest_score' => $query->min('total_score')
        ];
    }

    public function getMonthlyTrends($year = null)
    {
        $year = $year ?? now()->year;
        
        return $this->model->selectRaw('MONTH(created_at) as month, AVG(total_score) as avg_score')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getComplianceSummary()
    {
        return $this->model->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "compliant" THEN 1 ELSE 0 END) as compliant,
            SUM(CASE WHEN status = "non_compliant" THEN 1 ELSE 0 END) as non_compliant
        ')->first();
    }
}