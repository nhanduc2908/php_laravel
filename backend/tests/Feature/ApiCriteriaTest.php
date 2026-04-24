<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Criteria;
use App\Models\Category;

class ApiCriteriaTest extends TestCase
{
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->actingAsAdmin();
    }

    public function test_can_get_criteria_list()
    {
        Criteria::factory()->count(5)->create();
        
        $response = $this->getJson('/api/v1/criteria');
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status', 'data' => ['data', 'current_page', 'total']
                 ]);
    }

    public function test_can_create_criteria()
    {
        $category = Category::factory()->create();
        
        $response = $this->postJson('/api/v1/criteria', [
            'category_id' => $category->id,
            'code' => 'TEST-001',
            'name' => 'Test Criteria',
            'weight' => 5,
            'status' => 'active'
        ]);
        
        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('criteria', ['code' => 'TEST-001']);
    }

    public function test_can_update_criteria()
    {
        $criteria = Criteria::factory()->create(['name' => 'Old Name']);
        
        $response = $this->putJson("/api/v1/criteria/{$criteria->id}", [
            'name' => 'New Name',
            'weight' => 10
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('criteria', ['id' => $criteria->id, 'name' => 'New Name']);
    }

    public function test_can_delete_criteria()
    {
        $criteria = Criteria::factory()->create();
        
        $response = $this->deleteJson("/api/v1/criteria/{$criteria->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseMissing('criteria', ['id' => $criteria->id]);
    }
}