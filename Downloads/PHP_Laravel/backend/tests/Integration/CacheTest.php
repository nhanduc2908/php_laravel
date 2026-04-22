<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class CacheTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_cache_can_store_and_retrieve()
    {
        Cache::put('test_key', 'test_value', 60);
        
        $value = Cache::get('test_key');
        
        $this->assertEquals('test_value', $value);
    }

    public function test_cache_remembers_values()
    {
        $counter = 0;
        
        $value = Cache::remember('remember_key', 60, function () use (&$counter) {
            $counter++;
            return 'cached_value';
        });
        
        $this->assertEquals('cached_value', $value);
        $this->assertEquals(1, $counter);
        
        $value2 = Cache::remember('remember_key', 60, function () use (&$counter) {
            $counter++;
            return 'new_value';
        });
        
        $this->assertEquals('cached_value', $value2);
        $this->assertEquals(1, $counter);
    }

    public function test_cache_tags_work()
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            Cache::tags(['users'])->put('user:1', 'John', 60);
            Cache::tags(['users'])->put('user:2', 'Jane', 60);
            
            $this->assertEquals('John', Cache::tags(['users'])->get('user:1'));
            $this->assertEquals('Jane', Cache::tags(['users'])->get('user:2'));
            
            Cache::tags(['users'])->flush();
            
            $this->assertNull(Cache::tags(['users'])->get('user:1'));
            $this->assertNull(Cache::tags(['users'])->get('user:2'));
        } else {
            $this->markTestSkipped('Cache tags not supported by current driver');
        }
    }

    public function test_cache_increment_decrement()
    {
        Cache::put('counter', 0, 60);
        
        Cache::increment('counter');
        $this->assertEquals(1, Cache::get('counter'));
        
        Cache::increment('counter', 5);
        $this->assertEquals(6, Cache::get('counter'));
        
        Cache::decrement('counter', 2);
        $this->assertEquals(4, Cache::get('counter'));
    }
}