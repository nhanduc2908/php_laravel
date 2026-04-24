<?php

namespace App\Console\Commands;

use App\Services\ReportGeneratorService;
use Illuminate\Console\Command;

class GenerateReportCommand extends Command
{
    protected $signature = 'report:generate {server_id} {--format=pdf}';
    protected $description = 'Generate security report for a server';

    public function handle(ReportGeneratorService $reportService)
    {
        $serverId = $this->argument('server_id');
        $format = $this->option('format');
        
        $this->info("Generating {$format} report for server ID: {$serverId}");
        
        $report = $reportService->generate($serverId, $format);
        
        $this->info("Report generated: {$report['path']}");
        return Command::SUCCESS;
    }
}