<?php

namespace App\Wrappers;

class CryptoWrapper
{
    protected $key;

    public function __construct()
    {
        $this->key = env('APP_KEY');
    }

    public function encrypt($data)
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public function decrypt($data)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, 0, $iv);
    }

    public function hash($data)
    {
        return password_hash($data, PASSWORD_BCRYPT);
    }

    public function verify($data, $hash)
    {
        return password_verify($data, $hash);
    }
}