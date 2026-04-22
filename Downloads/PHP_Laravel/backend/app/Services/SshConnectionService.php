<?php

namespace App\Services;

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;
use App\Models\Server;
use Illuminate\Support\Facades\Log;

class SshConnectionService
{
    protected $connection;
    
    public function connect(Server $server)
    {
        try {
            $this->connection = new SSH2($server->host, $server->port);
            
            if ($server->ssh_key) {
                $rsa = new RSA();
                $rsa->loadKey($server->ssh_key);
                $auth = $this->connection->login($server->username, $rsa);
            } else {
                $auth = $this->connection->login($server->username, $server->password);
            }
            
            if (!$auth) {
                throw new \Exception('SSH authentication failed');
            }
            
            return $this->connection;
            
        } catch (\Exception $e) {
            Log::error("SSH connection failed: {$e->getMessage()}");
            throw $e;
        }
    }
    
    public function exec($connection, $command)
    {
        try {
            return $connection->exec($command);
        } catch (\Exception $e) {
            Log::error("SSH command failed: {$command} - {$e->getMessage()}");
            throw $e;
        }
    }
    
    public function disconnect()
    {
        if ($this->connection) {
            $this->connection->disconnect();
        }
    }
    
    public function testConnection(Server $server)
    {
        try {
            $this->connect($server);
            $result = $this->exec($this->connection, 'echo "OK"');
            $this->disconnect();
            
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => trim($result)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
    }
    
    public function executeCommand(Server $server, $command)
    {
        $connection = $this->connect($server);
        $output = $this->exec($connection, $command);
        $this->disconnect();
        
        return $output;
    }
}