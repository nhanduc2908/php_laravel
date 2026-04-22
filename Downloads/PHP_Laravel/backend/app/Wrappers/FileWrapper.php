<?php

namespace App\Wrappers;

class FileWrapper
{
    public function upload($file, $destination)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Invalid upload');
        }
        $filename = uniqid() . '_' . basename($file['name']);
        $path = rtrim($destination, '/') . '/' . $filename;
        move_uploaded_file($file['tmp_name'], $path);
        return $path;
    }

    public function delete($path)
    {
        if (file_exists($path)) {
            unlink($path);
            return true;
        }
        return false;
    }

    public function size($path)
    {
        return file_exists($path) ? filesize($path) : 0;
    }
}