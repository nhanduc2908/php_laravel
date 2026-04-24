<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Alert;

class ApiAlertTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->actingAsUser();
    }

    public function test_can_get_alerts_list()
    {
        Alert::factory()->count(5)->create();
        
        $response = $this->getJson('/api/v1/alerts');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_mark_alert_as_read()
    {
        $alert = Alert::factory()->create(['is_read' => false]);
        
        $response = $this->postJson("/api/v1/alerts/{$alert->id}/read");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('alerts', ['id' => $alert->id, 'is_read' => true]);
    }

    public function test_can_resolve_alert()
    {
        $alert = Alert::factory()->create(['is_resolved' => false]);
        
        $response = $this->postJson("/api/v1/alerts/{$alert->id}/resolve");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('alerts', ['id' => $alert->id, 'is_resolved' => true]);
    }
}