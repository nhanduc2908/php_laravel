<?php

namespace App\Http\Controllers;

use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportGenerator;

    public function __construct(ReportGeneratorService $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:servers,id',
            'format' => 'required|in:pdf,excel,csv',
            'type' => 'required|in:full,summary,compliance'
        ]);

        $report = $this->reportGenerator->generate($request->all());
        return $this->success($report, 'Report generated');
    }

    public function download($id)
    {
        return $this->reportGenerator->download($id);
    }

    public function exportPdf(Request $request)
    {
        $request->validate(['server_id' => 'required']);
        return $this->reportGenerator->exportPdf($request->server_id);
    }

    public function exportExcel(Request $request)
    {
        $request->validate(['server_id' => 'required']);
        return $this->reportGenerator->exportExcel($request->server_id);
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'server_id' => 'required',
            'frequency' => 'required|in:daily,weekly,monthly',
            'format' => 'required|in:pdf,excel'
        ]);

        $schedule = $this->reportGenerator->schedule($request->all());
        return $this->success($schedule, 'Report scheduled');
    }

    public function templates()
    {
        $templates = $this->reportGenerator->getTemplates();
        return $this->success($templates, 'Report templates');
    }
}
