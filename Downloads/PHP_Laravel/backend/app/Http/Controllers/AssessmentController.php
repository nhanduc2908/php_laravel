<?php

namespace App\Http\Controllers;

use App\Services\AssessmentService;
use App\Services\ScoreCalculatorService;
use App\Http\Requests\AssessmentRequest;

class AssessmentController extends Controller
{
    protected $assessment;
    protected $calculator;

    public function __construct(AssessmentService $assessment, ScoreCalculatorService $calculator)
    {
        $this->assessment = $assessment;
        $this->calculator = $calculator;
    }

    public function run(AssessmentRequest $request)
    {
        $result = $this->assessment->run($request->server_id, $request->answers);
        return $this->success($result, 'Assessment completed');
    }

    public function result($id)
    {
        $result = $this->assessment->getResult($id);
        return $this->success($result, 'Assessment result retrieved');
    }

    public function history(Request $request)
    {
        $history = $this->assessment->getHistory($request->server_id);
        return $this->success($history, 'Assessment history');
    }

    public function compliance($serverId)
    {
        $compliance = $this->calculator->calculateCompliance($serverId);
        return $this->success($compliance, 'Compliance status');
    }

    public function score($serverId)
    {
        $score = $this->calculator->calculateScore($serverId);
        return $this->success(['score' => $score], 'Server score');
    }

    public function compare(Request $request)
    {
        $comparison = $this->assessment->compare($request->assessment_ids);
        return $this->success($comparison, 'Comparison result');
    }

    public function export($id)
    {
        return $this->assessment->export($id);
    }
}