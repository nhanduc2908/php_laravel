<?php

namespace App\Wrappers;

use Redis;

class RedisWrapper
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect(config('database.redis.host'), config('database.redis.port'));
        if ($password = config('database.redis.password')) {
            $this->redis->auth($password);
        }
    }

    public function get($key) { return $this->redis->get($key); }
    public function set($key, $value, $ttl = null) { return $ttl ? $this->redis->setex($key, $ttl, $value) : $this->redis->set($key, $value); }
    public function del($key) { return $this->redis->del($key); }
    public function incr($key) { return $this->redis->incr($key); }
    public function hset($hash, $key, $value) { return $this->redis->hSet($hash, $key, $value); }
    public function hget($hash, $key) { return $this->redis->hGet($hash, $key); }
}