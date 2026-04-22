<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiBackupTest extends TestCase
{
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->actingAsAdmin();
    }

    public function test_can_create_backup()
    {
        $response = $this->postJson('/api/v1/backup/create', [
            'type' => 'database'
        ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data' => ['filename', 'size']]);
    }

    public function test_can_get_backups_list()
    {
        $response = $this->getJson('/api/v1/backup/list');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }
}