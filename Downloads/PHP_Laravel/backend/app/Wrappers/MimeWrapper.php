<?php

namespace App\Wrappers;

class MimeWrapper
{
    protected $mimes = [
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'txt' => 'text/plain',
        'html' => 'text/html',
        'json' => 'application/json',
    ];

    public function getMimeType($extension)
    {
        return $this->mimes[$extension] ?? 'application/octet-stream';
    }

    public function getExtension($mimeType)
    {
        return array_search($mimeType, $this->mimes) ?: 'bin';
    }
}