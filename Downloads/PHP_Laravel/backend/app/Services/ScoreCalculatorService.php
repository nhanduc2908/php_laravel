<?php

namespace App\Services;

use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;

class ScoreCalculatorService
{
    public function calculateScore(Server $server)
    {
        $criteria = Criteria::with('category')->get();
        $totalWeight = $criteria->sum('weight');
        
        $results = AssessmentResult::where('server_id', $server->id)
            ->whereIn('criteria_id', $criteria->pluck('id'))
            ->get()
            ->keyBy('criteria_id');
        
        $earnedScore = 0;
        $details = [];
        
        foreach ($criteria as $criterion) {
            $result = $results[$criterion->id] ?? null;
            $score = $result ? $result->score : 0;
            $earnedScore += $score;
            
            $details[] = [
                'criteria_id' => $criterion->id,
                'code' => $criterion->code,
                'name' => $criterion->name,
                'weight' => $criterion->weight,
                'score' => $score,
                'percentage' => $totalWeight > 0 ? ($score / $criterion->weight) * 100 : 0
            ];
        }
        
        $totalScore = $totalWeight > 0 ? ($earnedScore / $totalWeight) * 100 : 0;
        
        return [
            'server_id' => $server->id,
            'total_score' => round($totalScore, 2),
            'earned_score' => round($earnedScore, 2),
            'max_score' => round($totalWeight, 2),
            'details' => $details,
            'grade' => $this->getGrade($totalScore)
        ];
    }
    
    public function calculateCompliance(Server $server)
    {
        $criteria = Criteria::count();
        $compliant = AssessmentResult::where('server_id', $server->id)
            ->where('score', '>', 0)
            ->count();
        
        return [
            'server_id' => $server->id,
            'total_criteria' => $criteria,
            'compliant_criteria' => $compliant,
            'non_compliant_criteria' => $criteria - $compliant,
            'compliance_rate' => $criteria > 0 ? round(($compliant / $criteria) * 100, 2) : 0
        ];
    }
    
    public function calculateCategoryScore(Server $server)
    {
        $categories = \App\Models\Category::with('criteria')->get();
        $results = [];
        
        foreach ($categories as $category) {
            $categoryWeight = $category->criteria->sum('weight');
            $earnedScore = 0;
            
            foreach ($category->criteria as $criterion) {
                $result = AssessmentResult::where('server_id', $server->id)
                    ->where('criteria_id', $criterion->id)
                    ->first();
                $earnedScore += $result ? $result->score : 0;
            }
            
            $score = $categoryWeight > 0 ? ($earnedScore / $categoryWeight) * 100 : 0;
            
            $results[] = [
                'category_id' => $category->id,
                'name' => $category->name,
                'score' => round($score, 2),
                'weight' => $categoryWeight
            ];
        }
        
        return $results;
    }
    
    protected function getGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}