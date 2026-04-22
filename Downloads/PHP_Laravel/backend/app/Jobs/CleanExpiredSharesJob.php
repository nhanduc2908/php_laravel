<?php

namespace App\Jobs;

use App\Models\AssessmentFileShare;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class CleanExpiredSharesJob extends BaseJob
{
    public function handle(NotificationService $notification)
    {
        $expiredShares = AssessmentFileShare::where('expires_at', '<', now())->get();
        
        $deleted = 0;
        
        foreach ($expiredShares as $share) {
            // Notify user that share expired
            $notification->sendPush($share->shared_with, 'Share Expired', "Access to file has expired");
            
            $share->delete();
            $deleted++;
        }
        
        Log::info('Expired shares cleaned', ['deleted' => $deleted]);
    }
}