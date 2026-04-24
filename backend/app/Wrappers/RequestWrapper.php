<?php

namespace App\Wrappers;

class RequestWrapper
{
    protected $method;
    protected $uri;
    protected $params;
    protected $headers;
    protected $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->params = array_merge($_GET, $_POST);
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
    }

    public function getMethod() { return $this->method; }
    public function getUri() { return $this->uri; }
    public function input($key, $default = null) { return $this->params[$key] ?? $default; }
    public function all() { return $this->params; }
    public function header($key, $default = null) { return $this->headers[$key] ?? $default; }
    public function json() { return json_decode($this->body, true); }
}