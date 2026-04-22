<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiDashboardTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->actingAsUser();
    }

    public function test_can_get_dashboard_stats()
    {
        $response = $this->getJson('/api/v1/dashboard/stats');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status', 'data' => ['servers', 'assessments', 'vulnerabilities']
                 ]);
    }

    public function test_can_get_chart_data()
    {
        $response = $this->getJson('/api/v1/dashboard/charts');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_get_recent_activities()
    {
        $response = $this->getJson('/api/v1/dashboard/recent');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }
}