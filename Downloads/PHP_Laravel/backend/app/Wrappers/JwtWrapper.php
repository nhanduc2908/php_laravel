<?php

namespace App\Wrappers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtWrapper
{
    protected $secret;
    protected $algo;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET');
        $this->algo = env('JWT_ALGO', 'HS256');
    }

    public function encode($payload, $ttl = 3600)
    {
        $payload['exp'] = time() + $ttl;
        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function decode($token)
    {
        try {
            return (array) JWT::decode($token, new Key($this->secret, $this->algo));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refresh($token)
    {
        $decoded = $this->decode($token);
        if (!$decoded) return null;
        unset($decoded['exp']);
        return $this->encode($decoded);
    }
}