<?php

namespace App\Wrappers;

class DownloadWrapper
{
    public function force($path, $name = null)
    {
        $name = $name ?: basename($path);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function inline($path, $name = null)
    {
        $name = $name ?: basename($path);
        header('Content-Type: ' . mime_content_type($path));
        header('Content-Disposition: inline; filename="' . $name . '"');
        readfile($path);
        exit;
    }
}