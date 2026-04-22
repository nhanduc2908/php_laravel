<?php

namespace Tests\E2E;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class UserManagementFlowTest extends TestCase
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
     * E2E: Complete user management flow (CRUD + role assignment)
     */
    public function complete_user_management_flow()
    {
        // ========== STEP 1: Create new user ==========
        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/v1/users', [
                'name' => 'E2E Test User',
                'email' => 'e2e@test.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'role_id' => 3
            ]);

        $createResponse->assertStatus(201);
        $userId = $createResponse->json('data.id');
        $this->assertDatabaseHas('users', ['email' => 'e2e@test.com']);

        // ========== STEP 2: List all users ==========
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->getJson('/api/v1/users');

        $listResponse->assertStatus(200);
        $users = $listResponse->json('data.data');
        $this->assertGreaterThan(0, count($users));

        // ========== STEP 3: Get user details ==========
        $detailResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->getJson("/api/v1/users/{$userId}");

        $detailResponse->assertStatus(200);
        $detailResponse->assertJson(['data' => ['name' => 'E2E Test User']]);

        // ========== STEP 4: Update user ==========
        $updateResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->putJson("/api/v1/users/{$userId}", [
                'name' => 'Updated E2E User',
                'email' => 'e2e_updated@test.com'
            ]);

        $updateResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $userId, 'name' => 'Updated E2E User']);

        // ========== STEP 5: Assign role to user ==========
        $roleResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson("/api/v1/users/{$userId}/assign-role/4");

        $roleResponse->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $userId, 'role_id' => 4]);

        // ========== STEP 6: User logs in with new role ==========
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'e2e_updated@test.com',
            'password' => 'Password123!'
        ]);

        $loginResponse->assertStatus(200);
        $userToken = $loginResponse->json('data.access_token');

        // ========== STEP 7: Check user permissions based on role ==========
        $meResponse = $this->withHeader('Authorization', 'Bearer ' . $userToken)
            ->getJson('/api/v1/auth/me');

        $meResponse->assertStatus(200);
        $meResponse->assertJson(['data' => ['role_id' => 4]]);

        // ========== STEP 8: User updates own profile ==========
        $profileResponse = $this->withHeader('Authorization', 'Bearer ' . $userToken)
            ->putJson('/api/v1/profile', [
                'name' => 'Self Updated User'
            ]);

        $profileResponse->assertStatus(200);

        // ========== STEP 9: User changes password ==========
        $passwordResponse = $this->withHeader('Authorization', 'Bearer ' . $userToken)
            ->postJson('/api/v1/profile/change-password', [
                'current_password' => 'Password123!',
                'new_password' => 'NewPassword456!',
                'new_password_confirmation' => 'NewPassword456!'
            ]);

        $passwordResponse->assertStatus(200);

        // ========== STEP 10: User logs in with new password ==========
        $newLoginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'e2e_updated@test.com',
            'password' => 'NewPassword456!'
        ]);

        $newLoginResponse->assertStatus(200);

        // ========== STEP 11: Admin deletes user ==========
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->deleteJson("/api/v1/users/{$userId}");

        $deleteResponse->assertStatus(200);
        $this->assertSoftDeleted('users', ['id' => $userId]);
    }
}