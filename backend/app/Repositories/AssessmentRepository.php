<?php

namespace App\Repositories;

use App\Models\AssessmentResult;
use App\Models\AssessmentReport;

class AssessmentRepository extends BaseRepository
{
    public function model()
    {
        return AssessmentResult::class;
    }

    public function getHistory($serverId, $perPage = 20)
    {
        return AssessmentReport::where('server_id', $serverId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getLatestScore($serverId)
    {
        return AssessmentReport::where('server_id', $serverId)
            ->latest()
            ->first();
    }

    public function saveResults($serverId, $results)
    {
        foreach ($results as $result) {
            $this->model->updateOrCreate(
                [
                    'server_id' => $serverId,
                    'criteria_id' => $result['criteria_id']
                ],
                [
                    'score' => $result['score'],
                    'evidence' => $result['evidence'] ?? null,
                    'note' => $result['note'] ?? null,
                    'assessed_by' => auth()->id()
                ]
            );
        }
        
        return $this->calculateAndSaveReport($serverId);
    }

    public function calculateAndSaveReport($serverId)
    {
        $results = $this->model->where('server_id', $serverId)->get();
        $totalScore = $results->avg('score') ?? 0;
        $compliancePercent = $results->where('score', '>', 0)->count() / max($results->count(), 1) * 100;
        
        return AssessmentReport::create([
            'server_id' => $serverId,
            'total_score' => round($totalScore, 2),
            'compliance_percent' => round($compliancePercent, 2),
            'status' => $compliancePercent >= 70 ? 'compliant' : 'non_compliant',
            'created_by' => auth()->id(),
            'completed_at' => now()
        ]);
    }

    public function getComplianceStats($serverId = null)
    {
        $query = AssessmentReport::query();
        
        if ($serverId) {
            $query->where('server_id', $serverId);
        }
        
        $reports = $query->get();
        
        return [
            'total_assessments' => $reports->count(),
            'average_score' => $reports->avg('total_score'),
            'compliant_count' => $reports->where('status', 'compliant')->count(),
            'non_compliant_count' => $reports->where('status', 'non_compliant')->count()
        ];
    }

    public function getTrends($serverId, $months = 6)
    {
        return AssessmentReport::where('server_id', $serverId)
            ->where('created_at', '>=', now()->subMonths($months))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m');
            })
            ->map(function ($group) {
                return $group->avg('total_score');
            });
    }
}