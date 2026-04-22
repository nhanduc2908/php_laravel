<?php

namespace App\Services;

use App\Models\Server;
use App\Models\AssessmentReport;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportGeneratorService
{
    protected $scoreService;
    
    public function __construct(ScoreCalculatorService $scoreService)
    {
        $this->scoreService = $scoreService;
    }
    
    public function generate($serverId, $format = 'pdf')
    {
        $server = Server::findOrFail($serverId);
        $score = $this->scoreService->calculateScore($server);
        $compliance = $this->scoreService->calculateCompliance($server);
        $categoryScores = $this->scoreService->calculateCategoryScore($server);
        
        $data = [
            'server' => $server,
            'score' => $score,
            'compliance' => $compliance,
            'category_scores' => $categoryScores,
            'generated_at' => now()
        ];
        
        if ($format == 'pdf') {
            return $this->generatePDF($data);
        } elseif ($format == 'excel') {
            return $this->generateExcel($data);
        }
        
        return $data;
    }
    
    protected function generatePDF($data)
    {
        $pdf = Pdf::loadView('reports.assessment', $data);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = "assessment_report_{$data['server']->id}_{$data['generated_at']->format('Ymd_His')}.pdf";
        $path = storage_path("app/reports/{$filename}");
        
        $pdf->save($path);
        
        return [
            'filename' => $filename,
            'path' => $path,
            'size' => filesize($path)
        ];
    }
    
    protected function generateExcel($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'Assessment Report');
        $sheet->setCellValue('A2', 'Server: ' . $data['server']->name);
        $sheet->setCellValue('A3', 'Date: ' . $data['generated_at']->format('Y-m-d H:i:s'));
        $sheet->setCellValue('A5', 'Total Score: ' . $data['score']['total_score'] . '%');
        $sheet->setCellValue('A6', 'Compliance Rate: ' . $data['compliance']['compliance_rate'] . '%');
        
        // Category scores
        $sheet->setCellValue('A8', 'Category Scores');
        $sheet->setCellValue('A9', 'Category');
        $sheet->setCellValue('B9', 'Score');
        
        $row = 10;
        foreach ($data['category_scores'] as $category) {
            $sheet->setCellValue('A' . $row, $category['name']);
            $sheet->setCellValue('B' . $row, $category['score'] . '%');
            $row++;
        }
        
        $filename = "assessment_report_{$data['server']->id}_{$data['generated_at']->format('Ymd_His')}.xlsx";
        $path = storage_path("app/reports/{$filename}");
        
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        
        return [
            'filename' => $filename,
            'path' => $path,
            'size' => filesize($path)
        ];
    }
    
    public function download($reportId)
    {
        $report = AssessmentReport::findOrFail($reportId);
        $path = storage_path("app/reports/{$report->filename}");
        
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        throw new \Exception('Report file not found');
    }
    
    public function schedule($data)
    {
        // Schedule report generation
        \App\Models\ReportSchedule::create($data);
        return true;
    }
    
    public function getTemplates()
    {
        return [
            'full' => 'Full Assessment Report',
            'summary' => 'Summary Report',
            'compliance' => 'Compliance Report',
            'vulnerability' => 'Vulnerability Report'
        ];
    }
}