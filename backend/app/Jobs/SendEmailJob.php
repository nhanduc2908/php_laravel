<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendEmailJob extends BaseJob
{
    protected $to;
    protected $subject;
    protected $view;
    protected $data;

    public function __construct($to, $subject, $view, $data = [])
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    public function handle(NotificationService $notification)
    {
        $result = $notification->sendEmail($this->to, $this->subject, $this->view, $this->data);
        
        if (!$result) {
            Log::warning('Email sending failed, will retry', [
                'to' => $this->to,
                'subject' => $this->subject
            ]);
            throw new \Exception('Email sending failed');
        }
        
        Log::info('Email sent', ['to' => $this->to, 'subject' => $this->subject]);
    }
}