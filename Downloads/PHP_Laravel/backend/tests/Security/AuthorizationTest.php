<?php

namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\AssessmentFile;

class AuthorizationTest extends TestCase
{
    protected $admin;
    protected $securityOfficer;
    protected $viewer;
    protected $auditor;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role_id' => 2]);
        $this->securityOfficer = User::factory()->create(['role_id' => 3]);
        $this->viewer = User::factory()->create(['role_id' => 4]);
        $this->auditor = User::factory()->create(['role_id' => 5]);
    }

    public function test_viewer_cannot_create_users()
    {
        $token = auth()->login($this->viewer);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/users', [
                'name' => 'New User',
                'email' => 'new@example.com',
                'password' => 'password123',
                'role_id' => 3
            ]);
        
        $response->assertStatus(403);
    }

    public function test_security_officer_cannot_view_audit_logs()
    {
        $token = auth()->login($this->securityOfficer);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/audit/logs');
        
        $response->assertStatus(403);
    }

    public function test_admin_can_manage_all_servers()
    {
        $token = auth()->login($this->admin);
        $server = Server::factory()->create();
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/v1/servers/{$server->id}");
        
        $response->assertStatus(200);
    }

    public function test_user_can_only_access_own_files()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $file = AssessmentFile::factory()->create(['created_by' => $user1->id]);
        
        $token2 = auth()->login($user2);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token2)
            ->getJson("/api/v1/assessment-files/{$file->id}");
        
        $response->assertStatus(403);
    }

    public function test_auditor_can_view_audit_logs()
    {
        $token = auth()->login($this->auditor);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/audit/logs');
        
        $response->assertStatus(200);
    }

    public function test_auditor_cannot_modify_system_settings()
    {
        $token = auth()->login($this->auditor);
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/v1/settings', [
                'app_name' => 'Hacked Name'
            ]);
        
        $response->assertStatus(403);
    }

    public function test_user_cannot_access_other_users_files()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $file = AssessmentFile::factory()->create(['created_by' => $user1->id]);
        
        $token2 = auth()->login($user2);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token2)
            ->getJson("/api/v1/assessment-files/{$file->id}");
        
        $response->assertStatus(403);
    }

    public function test_role_based_menu_access()
    {
        $roles = [
            'super_admin' => ['dashboard', 'users', 'roles', 'servers', 'criteria', 'assessments', 'files', 'reports', 'audit', 'settings', 'testing'],
            'admin' => ['dashboard', 'users', 'servers', 'criteria', 'assessments', 'files', 'reports'],
            'security_officer' => ['dashboard', 'servers', 'assessments', 'files', 'reports'],
            'viewer' => ['dashboard', 'reports'],
            'auditor' => ['dashboard', 'audit', 'reports']
        ];
        
        foreach ($roles as $role => $expectedMenus) {
            $user = User::factory()->create(['role_id' => $this->getRoleId($role)]);
            $token = auth()->login($user);
            
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/v1/menu');
            
            $response->assertStatus(200);
            $menus = array_column($response->json('data'), 'title');
            
            foreach ($expectedMenus as $menu) {
                $this->assertContains(ucfirst($menu), $menus, "Role {$role} missing {$menu}");
            }
        }
    }

    protected function getRoleId($role)
    {
        $roles = [
            'super_admin' => 1,
            'admin' => 2,
            'security_officer' => 3,
            'viewer' => 4,
            'auditor' => 5
        ];
        
        return $roles[$role] ?? 4;
    }
}