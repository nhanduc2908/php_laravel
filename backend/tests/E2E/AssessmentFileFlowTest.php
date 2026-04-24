<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\AssessmentFile;
use Illuminate\Http\UploadedFile;

class AssessmentFileFlowTest extends TestCase
{
    protected $securityOfficer;
    protected $admin;
    protected $officerToken;
    protected $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->officerToken = auth()->login($this->securityOfficer);
        $this->adminToken = auth()->login($this->admin);
    }

    /**
     * @test
     * E2E: Complete assessment file management flow
     */
    public function complete_assessment_file_flow()
    {
        $server = Server::factory()->create();

        // ========== STEP 1: Create a new file ==========
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson('/api/v1/assessment-files', [
                'title' => 'E2E Assessment Document',
                'content' => 'This is the initial content of the assessment document.',
                'server_id' => $server->id,
                'status' => 'draft',
                'priority' => 'high',
                'tags' => ['security', 'assessment', 'e2e']
            ]);

        $createResponse->assertStatus(201);
        $fileId = $createResponse->json('data.id');
        $this->assertDatabaseHas('assessment_files', ['id' => $fileId, 'title' => 'E2E Assessment Document']);

        // ========== STEP 2: List all files ==========
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson('/api/v1/assessment-files');

        $listResponse->assertStatus(200);
        $listResponse->assertJsonStructure(['status', 'data' => ['data', 'total']]);

        // ========== STEP 3: Get file details ==========
        $detailResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/assessment-files/{$fileId}");

        $detailResponse->assertStatus(200);
        $detailResponse->assertJson(['data' => ['title' => 'E2E Assessment Document']]);

        // ========== STEP 4: Update file content (creates new version) ==========
        $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->putJson("/api/v1/assessment-files/{$fileId}", [
                'title' => 'Updated Assessment Document',
                'content' => 'This is the updated content after review.',
                'status' => 'published'
            ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('assessment_files', ['id' => $fileId, 'version' => 2]);

        // ========== STEP 5: Upload attachment ==========
        $file = UploadedFile::fake()->create('evidence.pdf', 1024);
        
        $uploadResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson("/api/v1/assessment-files/{$fileId}/upload", [
                'attachment' => $file
            ], ['Content-Type' => 'multipart/form-data']);

        $uploadResponse->assertStatus(200);

        // ========== STEP 6: Share file with admin ==========
        $shareResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson("/api/v1/assessment-files/{$fileId}/share/{$this->admin->id}");

        $shareResponse->assertStatus(200);
        $this->assertDatabaseHas('assessment_file_shares', [
            'file_id' => $fileId,
            'shared_with' => $this->admin->id
        ]);

        // ========== STEP 7: Admin views shared file ==========
        $adminViewResponse = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson("/api/v1/assessment-files/{$fileId}");

        $adminViewResponse->assertStatus(200);

        // ========== STEP 8: Get file version history ==========
        $versionsResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/assessment-files/{$fileId}/versions");

        $versionsResponse->assertStatus(200);
        $versionsResponse->assertJsonCount(2, 'data');

        // ========== STEP 9: Restore previous version ==========
        $versionId = $versionsResponse->json('data.1.id');
        $restoreResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->postJson("/api/v1/assessment-files/{$fileId}/restore/{$versionId}");

        $restoreResponse->assertStatus(200);

        // ========== STEP 10: Search files ==========
        $searchResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson('/api/v1/assessment-files?search=Assessment');

        $searchResponse->assertStatus(200);
        $searchResponse->assertJsonCount(1, 'data.data');

        // ========== STEP 11: Filter by status ==========
        $filterResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson('/api/v1/assessment-files?status=published');

        $filterResponse->assertStatus(200);

        // ========== STEP 12: Download file ==========
        $downloadResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->getJson("/api/v1/assessment-files/{$fileId}/download");

        $downloadResponse->assertStatus(200);

        // ========== STEP 13: Delete file ==========
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $this->officerToken)
            ->deleteJson("/api/v1/assessment-files/{$fileId}");

        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('assessment_files', ['id' => $fileId]);
    }
}