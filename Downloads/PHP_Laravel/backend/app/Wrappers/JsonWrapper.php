<?php

namespace App\Wrappers;

class JsonWrapper
{
    public function encode($data, $options = 0)
    {
        return json_encode($data, $options);
    }

    public function decode($json, $assoc = true)
    {
        return json_decode($json, $assoc);
    }

    public function response($data, $status = 200)
    {
        return (new ResponseWrapper())
            ->setStatusCode($status)
            ->setHeader('Content-Type', 'application/json')
            ->setContent($this->encode($data))
            ->send();
    }
}