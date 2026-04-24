<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Backup;

class BackupRestoreFlowTest extends TestCase
{
    protected $superAdmin;
    protected $superAdminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create(['role_id' => 1]);
        $this->superAdminToken = auth()->login($this->superAdmin);
    }

    /**
     * @test
     * E2E: Complete backup and restore flow
     */
    public function complete_backup_restore_flow()
    {
        // ========== STEP 1: Create test data ==========
        $server = Server::factory()->create(['name' => 'Backup Test Server']);
        $initialCount = Server::count();

        // ========== STEP 2: Create database backup ==========
        $backupResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/v1/backup/create', ['type' => 'database']);

        $backupResponse->assertStatus(200);
        $backupId = $backupResponse->json('data.id');
        $this->assertDatabaseHas('backups', ['id' => $backupId, 'type' => 'database']);

        // ========== STEP 3: List backups ==========
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->getJson('/api/v1/backup/list');

        $listResponse->assertStatus(200);
        $backups = $listResponse->json('data');
        $this->assertGreaterThanOrEqual(1, count($backups));

        // ========== STEP 4: Create another server (to be restored later) ==========
        $server2 = Server::factory()->create(['name' => 'Temporary Server']);
        $this->assertDatabaseHas('servers', ['name' => 'Temporary Server']);

        // ========== STEP 5: Delete temporary server ==========
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->deleteJson("/api/v1/servers/{$server2->id}");

        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('servers', ['id' => $server2->id]);

        // ========== STEP 6: Restore from backup ==========
        $restoreResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/v1/backup/restore', ['backup_id' => $backupId]);

        $restoreResponse->assertStatus(200);

        // ========== STEP 7: Download backup file ==========
        $downloadResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->getJson("/api/v1/backup/download/{$backupId}");

        $downloadResponse->assertStatus(200);

        // ========== STEP 8: Create files backup ==========
        $filesBackupResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/v1/backup/create', ['type' => 'files']);

        $filesBackupResponse->assertStatus(200);

        // ========== STEP 9: Create full backup (database + files) ==========
        $fullBackupResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/v1/backup/create', ['type' => 'both']);

        $fullBackupResponse->assertStatus(200);
        $this->assertDatabaseHas('backups', ['type' => 'both']);

        // ========== STEP 10: Delete a backup ==========
        $deleteBackupResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->deleteJson("/api/v1/backup/{$backupId}");

        $deleteBackupResponse->assertStatus(200);
        $this->assertDatabaseMissing('backups', ['id' => $backupId]);
    }
}