<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AssessmentFile;
use App\Models\User;
use App\Models\Server;

class AssessmentFileTest extends TestCase
{
    public function test_file_can_be_created()
    {
        $user = User::factory()->create();
        $server = Server::factory()->create();
        
        $file = AssessmentFile::create([
            'title' => 'Test File',
            'content' => 'Test content',
            'server_id' => $server->id,
            'created_by' => $user->id,
            'status' => 'draft',
            'version' => 1
        ]);
        
        $this->assertDatabaseHas('assessment_files', ['title' => 'Test File']);
        $this->assertEquals(1, $file->version);
    }

    public function test_file_has_versions()
    {
        $file = AssessmentFile::factory()->create(['version' => 1]);
        
        $file->createNewVersion('Updated content', $file->created_by);
        
        $this->assertEquals(2, $file->fresh()->version);
        $this->assertEquals(2, $file->versions()->count());
    }

    public function test_file_can_be_shared()
    {
        $file = AssessmentFile::factory()->create();
        $user = User::factory()->create();
        
        $share = $file->shares()->create([
            'shared_with' => $user->id,
            'permission' => 'view'
        ]);
        
        $this->assertTrue($file->isSharedWith($user->id));
        $this->assertDatabaseHas('assessment_file_shares', ['file_id' => $file->id]);
    }
}