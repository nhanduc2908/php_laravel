<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Server;

class ApiServerTest extends TestCase
{
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->actingAsAdmin();
    }

    public function test_can_create_server()
    {
        $response = $this->postJson('/api/v1/servers', [
            'name' => 'Test Server',
            'host' => '192.168.1.100',
            'port' => 22,
            'username' => 'admin',
            'status' => 'active'
        ]);
        
        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('servers', ['name' => 'Test Server']);
    }

    public function test_can_get_servers_list()
    {
        Server::factory()->count(5)->create();
        
        $response = $this->getJson('/api/v1/servers');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_update_server()
    {
        $server = Server::factory()->create(['name' => 'Old Name']);
        
        $response = $this->putJson("/api/v1/servers/{$server->id}", [
            'name' => 'New Name'
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('servers', ['id' => $server->id, 'name' => 'New Name']);
    }

    public function test_can_delete_server()
    {
        $server = Server::factory()->create();
        
        $response = $this->deleteJson("/api/v1/servers/{$server->id}");
        
        $response->assertStatus(200);
        $this->assertSoftDeleted('servers', ['id' => $server->id]);
    }
}