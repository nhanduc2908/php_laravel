<?php

namespace App\Http\Controllers;

use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function create(Request $request)
    {
        $request->validate(['type' => 'required|in:database,files,both']);
        $backup = $this->backupService->create($request->type);
        return $this->success($backup, 'Backup created');
    }

    public function restore(Request $request)
    {
        $request->validate(['backup_id' => 'required']);
        $result = $this->backupService->restore($request->backup_id);
        return $this->success($result, 'Restore completed');
    }

    public function list()
    {
        $backups = $this->backupService->list();
        return $this->success($backups, 'Backups retrieved');
    }

    public function download($id)
    {
        return $this->backupService->download($id);
    }

    public function delete($id)
    {
        $this->backupService->delete($id);
        return $this->success(null, 'Backup deleted');
    }
}