<?php

namespace App\Wrappers;

class FileCacheWrapper
{
    protected $path;

    public function __construct($path = null)
    {
        $this->path = $path ?: storage_path('cache');
        if (!is_dir($this->path)) mkdir($this->path, 0755, true);
    }

    public function get($key, $default = null)
    {
        $file = $this->path . '/' . md5($key) . '.cache';
        if (!file_exists($file)) return $default;
        $data = unserialize(file_get_contents($file));
        if ($data['expires'] < time()) {
            $this->delete($key);
            return $default;
        }
        return $data['value'];
    }

    public function set($key, $value, $ttl = 3600)
    {
        $file = $this->path . '/' . md5($key) . '.cache';
        $data = ['value' => $value, 'expires' => time() + $ttl];
        return file_put_contents($file, serialize($data));
    }

    public function delete($key)
    {
        $file = $this->path . '/' . md5($key) . '.cache';
        if (file_exists($file)) unlink($file);
        return true;
    }
}