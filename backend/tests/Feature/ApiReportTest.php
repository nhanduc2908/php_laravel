<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Server;
use App\Models\AssessmentReport;

class ApiReportTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->actingAsUser();
    }

    public function test_can_generate_report()
    {
        $server = Server::factory()->create();
        
        $response = $this->postJson('/api/v1/reports/generate', [
            'type' => 'full',
            'format' => 'pdf',
            'server_id' => $server->id
        ]);
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data' => ['filename', 'path']]);
    }

    public function test_can_download_report()
    {
        $report = AssessmentReport::factory()->create();
        
        $response = $this->getJson("/api/v1/reports/download/{$report->id}");
        
        $response->assertStatus(200);
    }

    public function test_can_schedule_report()
    {
        $server = Server::factory()->create();
        
        $response = $this->postJson('/api/v1/reports/schedule', [
            'server_id' => $server->id,
            'frequency' => 'daily',
            'format' => 'pdf',
            'recipient_email' => 'test@example.com'
        ]);
        
        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
    }
}