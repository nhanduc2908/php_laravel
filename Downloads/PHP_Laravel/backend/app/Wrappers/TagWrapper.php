<?php

namespace App\Wrappers;

class TagWrapper
{
    protected $cache;
    protected $tags = [];

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function tags($names)
    {
        $this->tags = (array) $names;
        return $this;
    }

    public function get($key, $default = null)
    {
        $taggedKey = $this->taggedKey($key);
        return $this->cache->get($taggedKey, $default);
    }

    public function set($key, $value, $ttl = 3600)
    {
        $taggedKey = $this->taggedKey($key);
        $this->cache->set($taggedKey, $value, $ttl);
        foreach ($this->tags as $tag) {
            $this->addTagMapping($tag, $taggedKey);
        }
        return true;
    }

    protected function taggedKey($key)
    {
        return 'tag:' . implode(':', $this->tags) . ':' . $key;
    }

    protected function addTagMapping($tag, $key)
    {
        $tagKey = "tag:{$tag}:keys";
        $keys = $this->cache->get($tagKey, []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            $this->cache->set($tagKey, $keys);
        }
    }

    public function flush($tag = null)
    {
        $tags = $tag ? [$tag] : $this->tags;
        foreach ($tags as $t) {
            $tagKey = "tag:{$t}:keys";
            $keys = $this->cache->get($tagKey, []);
            foreach ($keys as $key) {
                $this->cache->delete($key);
            }
            $this->cache->delete($tagKey);
        }
        return true;
    }
}