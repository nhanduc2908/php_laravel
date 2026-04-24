<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\ReportGeneratorService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class GenerateReportJob extends BaseJob
{
    protected $serverId;
    protected $userId;
    protected $format;

    public function __construct($serverId, $userId, $format = 'pdf')
    {
        $this->serverId = $serverId;
        $this->userId = $userId;
        $this->format = $format;
    }

    public function handle(ReportGeneratorService $reportGenerator, NotificationService $notification)
    {
        $server = Server::find($this->serverId);
        
        if (!$server) {
            Log::error('Server not found for report generation', ['server_id' => $this->serverId]);
            return;
        }
        
        $report = $reportGenerator->generate($server->id, $this->format);
        
        $notification->sendPush($this->userId, 'Report Ready', 'Your report has been generated', [
            'report_path' => $report['path'],
            'format' => $this->format
        ]);
        
        Log::info('Report generated', [
            'server_id' => $this->serverId,
            'user_id' => $this->userId,
            'format' => $this->format
        ]);
    }
}