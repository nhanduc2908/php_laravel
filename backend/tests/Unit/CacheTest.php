<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Wrappers\CacheWrapper;
use App\Wrappers\MemoryWrapper;

class CacheTest extends TestCase
{
    protected $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = new CacheWrapper(new MemoryWrapper());
    }

    public function test_set_and_get()
    {
        $this->cache->set('test_key', 'test_value', 3600);
        
        $value = $this->cache->get('test_key');
        
        $this->assertEquals('test_value', $value);
    }

    public function test_get_returns_default_when_key_not_found()
    {
        $value = $this->cache->get('non_existent_key', 'default');
        
        $this->assertEquals('default', $value);
    }

    public function test_delete_removes_key()
    {
        $this->cache->set('key_to_delete', 'value');
        $this->cache->delete('key_to_delete');
        
        $value = $this->cache->get('key_to_delete');
        
        $this->assertNull($value);
    }

    public function test_remember_caches_callback_result()
    {
        $callbackCalled = 0;
        
        $result1 = $this->cache->remember('remember_key', 3600, function () use (&$callbackCalled) {
            $callbackCalled++;
            return 'cached_value';
        });
        
        $result2 = $this->cache->remember('remember_key', 3600, function () use (&$callbackCalled) {
            $callbackCalled++;
            return 'new_value';
        });
        
        $this->assertEquals('cached_value', $result1);
        $this->assertEquals('cached_value', $result2);
        $this->assertEquals(1, $callbackCalled);
    }
}