<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Server;
use App\Models\Criteria;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\DB;

class DatabaseTest extends TestCase
{
    public function test_database_connection_is_working()
    {
        $this->assertTrue(DB::connection()->getPdo() !== null);
    }

    public function test_user_role_relationship()
    {
        $user = User::with('role')->first();
        
        $this->assertNotNull($user);
        $this->assertNotNull($user->role);
        $this->assertIsInt($user->role->id);
    }

    public function test_server_assessment_relationship()
    {
        $server = Server::factory()->create();
        AssessmentResult::factory()->count(3)->create(['server_id' => $server->id]);
        
        $serverWithAssessments = Server::with('assessments')->find($server->id);
        
        $this->assertCount(3, $serverWithAssessments->assessments);
    }

    public function test_criteria_category_relationship()
    {
        $criteria = Criteria::with('category')->first();
        
        $this->assertNotNull($criteria);
        $this->assertNotNull($criteria->category);
    }

    public function test_transaction_rollback_on_error()
    {
        DB::beginTransaction();
        
        try {
            User::factory()->create(['email' => 'transaction@test.com']);
            throw new \Exception('Forced error');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        
        $this->assertDatabaseMissing('users', ['email' => 'transaction@test.com']);
    }

    public function test_foreign_key_constraint()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        AssessmentResult::factory()->create(['server_id' => 99999]);
    }

    public function test_unique_constraint()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        User::factory()->create(['email' => 'unique@test.com']);
        User::factory()->create(['email' => 'unique@test.com']);
    }
}