<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\ScannerService;
use App\Services\SshConnectionService;
use App\Http\Requests\ServerRequest;

class ServerController extends Controller
{
    protected $scanner;
    protected $ssh;

    public function __construct(ScannerService $scanner, SshConnectionService $ssh)
    {
        $this->scanner = $scanner;
        $this->ssh = $ssh;
    }

    public function index()
    {
        $servers = Server::with('assessments')->paginate(15);
        return $this->success($servers, 'Servers retrieved');
    }

    public function store(ServerRequest $request)
    {
        $server = Server::create($request->validated());
        return $this->success($server, 'Server created', 201);
    }

    public function show($id)
    {
        $server = Server::with('assessments', 'vulnerabilities')->findOrFail($id);
        return $this->success($server, 'Server retrieved');
    }

    public function update(ServerRequest $request, $id)
    {
        $server = Server::findOrFail($id);
        $server->update($request->validated());
        return $this->success($server, 'Server updated');
    }

    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $server->delete();
        return $this->success(null, 'Server deleted');
    }

    public function testConnection($id)
    {
        $server = Server::findOrFail($id);
        $result = $this->ssh->testConnection($server);
        return $this->success($result, 'Connection test completed');
    }

    public function scan($id)
    {
        $server = Server::findOrFail($id);
        $result = $this->scanner->scan($server);
        return $this->success($result, 'Scan completed');
    }

    public function metrics($id)
    {
        $server = Server::findOrFail($id);
        $metrics = $this->scanner->getMetrics($server);
        return $this->success($metrics, 'Server metrics retrieved');
    }
}