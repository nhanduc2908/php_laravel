<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $realtimeService;
    
    public function __construct(RealtimeService $realtimeService)
    {
        $this->realtimeService = $realtimeService;
    }
    
    public function sendEmail($to, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendPush($userId, $title, $body, $data = [])
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'push',
            'title' => $title,
            'message' => $body,
            'data' => $data
        ]);
        
        $this->realtimeService->pushToUser($userId, 'notification.new', $notification);
        
        return $notification;
    }
    
    public function sendSMS($phone, $message)
    {
        // Implement SMS gateway integration
        Log::info("SMS to {$phone}: {$message}");
        return true;
    }
    
    public function notifyAssessmentComplete($userId, $assessmentId, $score)
    {
        $this->sendPush($userId, 'Assessment Complete', "Your assessment has completed with score: {$score}%", [
            'assessment_id' => $assessmentId,
            'score' => $score
        ]);
        
        $user = User::find($userId);
        $this->sendEmail($user->email, 'Assessment Complete', 'emails.assessment.complete', [
            'name' => $user->name,
            'score' => $score,
            'assessment_id' => $assessmentId
        ]);
    }
    
    public function notifyVulnerabilityFound($userId, $vulnerability)
    {
        $this->sendPush($userId, 'Vulnerability Found', "New vulnerability: {$vulnerability['name']}", $vulnerability);
    }
    
    public function notifyFileShared($userId, $file, $sharedBy)
    {
        $this->sendPush($userId, 'File Shared', "{$sharedBy} shared a file with you: {$file['title']}", $file);
    }
    
    public function notifySystemAlert($userId, $alert)
    {
        $this->sendPush($userId, 'System Alert', $alert['message'], $alert);
        $this->sendEmail(User::find($userId)->email, 'System Alert', 'emails.alert', $alert);
    }
}