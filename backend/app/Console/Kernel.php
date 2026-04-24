<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CreateAdminCommand::class,
        Commands\RunAssessmentCommand::class,
        Commands\GenerateReportCommand::class,
        Commands\BackupDatabaseCommand::class,
        Commands\CleanupLogsCommand::class,
        Commands\SyncServersCommand::class,
        Commands\TestCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Chạy backup mỗi ngày lúc 2h sáng
        $schedule->command('backup:run')->dailyAt('02:00');
        
        // Dọn log cũ mỗi ngày lúc 3h sáng
        $schedule->command('logs:clean')->dailyAt('03:00');
        
        // Đồng bộ server mỗi giờ
        $schedule->command('servers:sync')->hourly();
        
        // Chạy assessment tự động mỗi tuần vào Chủ Nhật
        $schedule->command('assessment:run --auto')->weekly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}