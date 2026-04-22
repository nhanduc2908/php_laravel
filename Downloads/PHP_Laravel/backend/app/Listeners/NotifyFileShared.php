<?php

namespace App\Listeners;

use App\Events\FileShared;
use App\Services\NotificationService;

class NotifyFileShared extends BaseListener
{
    protected $notification;

    public function __construct(NotificationService $notification)
    {
        $this->notification = $notification;
    }

    public function handle(FileShared $event)
    {
        $share = $event->share;
        $file = $share->file;
        $sharedBy = $file->creator;
        $sharedWith = \App\Models\User::find($share->shared_with);
        
        if (!$sharedWith) {
            return;
        }
        
        // Send push notification
        $this->notification->sendPush(
            $sharedWith->id,
            'File Shared',
            "{$sharedBy->name} shared a file with you: {$file->title}",
            [
                'file_id' => $file->id,
                'file_title' => $file->title,
                'shared_by' => $sharedBy->name,
                'permission' => $share->permission
            ]
        );
        
        // Send email
        $this->notification->sendEmail(
            $sharedWith->email,
            'File Shared with You',
            'emails.files.shared',
            [
                'user' => $sharedWith,
                'file' => $file,
                'shared_by' => $sharedBy,
                'permission' => $share->permission,
                'expires_at' => $share->expires_at
            ]
        );
    }
}