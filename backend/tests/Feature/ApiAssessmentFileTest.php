<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AssessmentFile;
use App\Models\Server;

class ApiAssessmentFileTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->actingAsUser();
    }

    public function test_can_create_file()
    {
        $server = Server::factory()->create();
        
        $response = $this->postJson('/api/v1/assessment-files', [
            'title' => 'Test File',
            'content' => 'This is test content',
            'server_id' => $server->id,
            'status' => 'draft'
        ]);
        
        $response->assertStatus(201)
                 ->assertJson(['status' => 'success']);
        
        $this->assertDatabaseHas('assessment_files', ['title' => 'Test File']);
    }

    public function test_can_get_files_list()
    {
        AssessmentFile::factory()->count(5)->create();
        
        $response = $this->getJson('/api/v1/assessment-files');
        
        $response->assertStatus(200)
                 ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_share_file()
    {
        $file = AssessmentFile::factory()->create(['created_by' => $this->user->id]);
        $otherUser = \App\Models\User::factory()->create();
        
        $response = $this->postJson("/api/v1/assessment-files/{$file->id}/share/{$otherUser->id}");
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('assessment_file_shares', [
            'file_id' => $file->id,
            'shared_with' => $otherUser->id
        ]);
    }

    public function test_can_get_file_versions()
    {
        $file = AssessmentFile::factory()->create();
        $file->createNewVersion('Version 2', $this->user->id);
        
        $response = $this->getJson("/api/v1/assessment-files/{$file->id}/versions");
        
        $response->assertStatus(200)
                 ->assertJsonCount(2, 'data');
    }
}