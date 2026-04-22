<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiLanguageTest extends TestCase
{
    public function test_can_switch_language()
    {
        $response = $this->withHeader('Accept-Language', 'vi')
                         ->getJson('/api/v1/menu');
        
        $response->assertStatus(200);
    }

    public function test_get_translations()
    {
        $response = $this->getJson('/api/v1/translations/en');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }
}