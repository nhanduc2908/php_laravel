<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Alert;
use Illuminate\Support\Facades\Cache;

class AttackDetectorService
{
    protected $thresholds = [
        'failed_logins' => 5,
        'requests_per_minute' => 100,
        'concurrent_sessions' => 3,
    ];
    
    public function detectBruteForce($ip, $email)
    {
        $key = "failed_login:{$ip}:{$email}";
        $attempts = Cache::increment($key);
        
        if ($attempts == 1) {
            Cache::put($key, 1, 3600);
        }
        
        if ($attempts >= $this->thresholds['failed_logins']) {
            $this->blockIP($ip, 'Brute force detected');
            $this->createAlert('brute_force', 'high', "Brute force attack detected from IP: {$ip}");
            return true;
        }
        
        return false;
    }
    
    public function detectDDoS($ip)
    {
        $key = "requests:{$ip}";
        $requests = Cache::increment($key);
        
        if ($requests == 1) {
            Cache::put($key, 1, 60);
        }
        
        if ($requests >= $this->thresholds['requests_per_minute']) {
            $this->blockIP($ip, 'DDoS attack detected');
            $this->createAlert('ddos', 'critical', "DDoS attack detected from IP: {$ip}");
            return true;
        }
        
        return false;
    }
    
    public function detectMultipleSessions($userId)
    {
        $key = "sessions:{$userId}";
        $sessions = Cache::get($key, 0);
        
        if ($sessions >= $this->thresholds['concurrent_sessions']) {
            $this->createAlert('multiple_sessions', 'medium', "User {$userId} has multiple concurrent sessions");
            return true;
        }
        
        return false;
    }
    
    public function detectSQLInjection($input)
    {
        $patterns = [
            '/(\%27)|(\')|(\-\-)/',
            '/(\%23)|(#)/',
            '/(\%3B)|(;)/',
            '/union.*select/i',
            '/or.*=.*=/i',
            '/and.*=.*=/i',
            '/drop.*table/i',
            '/delete.*from/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->createAlert('sql_injection', 'critical', "SQL Injection attempt detected: {$input}");
                return true;
            }
        }
        
        return false;
    }
    
    public function detectXSS($input)
    {
        $patterns = [
            '/<script.*<\/script>/i',
            '/on\w+="/i',
            '/javascript:/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->createAlert('xss', 'high', "XSS attempt detected: {$input}");
                return true;
            }
        }
        
        return false;
    }
    
    protected function blockIP($ip, $reason)
    {
        Cache::put("blocked_ip:{$ip}", $reason, 3600);
        \App\Models\BlockedIp::updateOrCreate(
            ['ip' => $ip],
            ['reason' => $reason, 'blocked_at' => now()]
        );
    }
    
    protected function createAlert($type, $severity, $message)
    {
        Alert::create([
            'type' => $type,
            'severity' => $severity,
            'title' => ucfirst(str_replace('_', ' ', $type)),
            'message' => $message,
            'source' => 'attack_detector'
        ]);
    }
}
