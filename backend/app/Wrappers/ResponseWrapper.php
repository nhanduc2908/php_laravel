<?php

namespace App\Wrappers;

class ResponseWrapper
{
    protected $statusCode = 200;
    protected $headers = [];
    protected $content = '';

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        echo $this->content;
        return $this;
    }

    public function redirect($url, $status = 302)
    {
        $this->setStatusCode($status)->setHeader('Location', $url)->send();
        exit;
    }
}