<?php

namespace App\Services;

use App\Models\Server;
use App\Models\Vulnerability;
use Illuminate\Support\Facades\Log;

class ScannerService
{
    protected $sshService;
    
    public function __construct(SshConnectionService $sshService)
    {
        $this->sshService = $sshService;
    }
    
    public function scan(Server $server)
    {
        try {
            $connection = $this->sshService->connect($server);
            
            $results = [
                'os' => $this->getOSVersion($connection),
                'packages' => $this->getInstalledPackages($connection),
                'openPorts' => $this->scanOpenPorts($server),
                'vulnerabilities' => $this->checkVulnerabilities($connection),
                'services' => $this->getRunningServices($connection),
            ];
            
            $this->saveVulnerabilities($server, $results['vulnerabilities']);
            
            $server->update(['last_scan_at' => now()]);
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Scan failed: ' . $e->getMessage());
            throw new \Exception('Scan failed: ' . $e->getMessage());
        }
    }
    
    protected function getOSVersion($connection)
    {
        $output = $this->sshService->exec($connection, 'cat /etc/os-release | grep PRETTY_NAME');
        preg_match('/"(.+)"/', $output, $matches);
        return $matches[1] ?? 'Unknown';
    }
    
    protected function getInstalledPackages($connection)
    {
        $output = $this->sshService->exec($connection, 'dpkg -l 2>/dev/null || rpm -qa');
        return explode("\n", $output);
    }
    
    protected function scanOpenPorts(Server $server)
    {
        $commonPorts = [21, 22, 23, 25, 53, 80, 443, 3306, 5432, 27017];
        $openPorts = [];
        
        foreach ($commonPorts as $port) {
            $connection = @fsockopen($server->host, $port, $errno, $errstr, 1);
            if ($connection) {
                $openPorts[] = $port;
                fclose($connection);
            }
        }
        
        return $openPorts;
    }
    
    protected function checkVulnerabilities($connection)
    {
        $vulnerabilities = [];
        
        // Check for common vulnerabilities
        $checks = [
            'ssh_root_login' => 'grep "PermitRootLogin yes" /etc/ssh/sshd_config',
            'password_authentication' => 'grep "PasswordAuthentication yes" /etc/ssh/sshd_config',
        ];
        
        foreach ($checks as $name => $command) {
            $output = $this->sshService->exec($connection, $command);
            if (!empty(trim($output))) {
                $vulnerabilities[] = [
                    'name' => $name,
                    'severity' => 'high',
                    'description' => "Vulnerability found: $name"
                ];
            }
        }
        
        return $vulnerabilities;
    }
    
    protected function getRunningServices($connection)
    {
        $output = $this->sshService->exec($connection, 'systemctl list-units --type=service --state=running');
        return explode("\n", $output);
    }
    
    protected function saveVulnerabilities($server, $vulnerabilities)
    {
        foreach ($vulnerabilities as $vuln) {
            Vulnerability::updateOrCreate(
                [
                    'server_id' => $server->id,
                    'name' => $vuln['name']
                ],
                [
                    'severity' => $vuln['severity'],
                    'description' => $vuln['description'],
                    'status' => 'open'
                ]
            );
        }
    }
}