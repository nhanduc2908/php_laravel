<?php

namespace Tests\Mock;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;

class DatabaseMockTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_db_connection_mock()
    {
        $mockConnection = Mockery::mock(\Illuminate\Database\Connection::class);
        
        $mockConnection->shouldReceive('table')
            ->once()
            ->with('users')
            ->andReturnSelf();
        
        $mockConnection->shouldReceive('select')
            ->once()
            ->andReturn([(object)['id' => 1, 'name' => 'Test User']]);
        
        DB::shouldReceive('connection')
            ->once()
            ->andReturn($mockConnection);
        
        $users = DB::connection()->table('users')->select();
        
        $this->assertCount(1, $users);
        $this->assertEquals('Test User', $users[0]->name);
    }

    public function test_query_builder_mock()
    {
        $mockQuery = Mockery::mock(\Illuminate\Database\Query\Builder::class);
        
        $mockQuery->shouldReceive('select')
            ->once()
            ->with(['id', 'name', 'email'])
            ->andReturnSelf();
        
        $mockQuery->shouldReceive('where')
            ->once()
            ->with('active', '=', 1)
            ->andReturnSelf();
        
        $mockQuery->shouldReceive('get')
            ->once()
            ->andReturn(collect([
                ['id' => 1, 'name' => 'User 1', 'email' => 'user1@test.com'],
                ['id' => 2, 'name' => 'User 2', 'email' => 'user2@test.com']
            ]));
        
        $result = $mockQuery->select(['id', 'name', 'email'])
            ->where('active', '=', 1)
            ->get();
        
        $this->assertCount(2, $result);
    }

    public function test_transaction_mock()
    {
        $mockConnection = Mockery::mock(\Illuminate\Database\Connection::class);
        
        $mockConnection->shouldReceive('beginTransaction')
            ->once()
            ->andReturnNull();
        
        $mockConnection->shouldReceive('commit')
            ->once()
            ->andReturnNull();
        
        $mockConnection->shouldReceive('rollBack')
            ->never();
        
        DB::shouldReceive('connection')
            ->once()
            ->andReturn($mockConnection);
        
        DB::connection()->beginTransaction();
        // Perform operations
        DB::connection()->commit();
        
        // Assertions passed if no exceptions
        $this->assertTrue(true);
    }

    public function test_transaction_mock_rollback()
    {
        $mockConnection = Mockery::mock(\Illuminate\Database\Connection::class);
        
        $mockConnection->shouldReceive('beginTransaction')
            ->once()
            ->andReturnNull();
        
        $mockConnection->shouldReceive('rollBack')
            ->once()
            ->andReturnNull();
        
        DB::shouldReceive('connection')
            ->once()
            ->andReturn($mockConnection);
        
        DB::connection()->beginTransaction();
        DB::connection()->rollBack();
        
        $this->assertTrue(true);
    }

    public function test_schema_mock()
    {
        $mockSchema = Mockery::mock();
        
        $mockSchema->shouldReceive('hasTable')
            ->once()
            ->with('users')
            ->andReturn(true);
        
        $mockSchema->shouldReceive('hasColumn')
            ->once()
            ->with('users', 'email')
            ->andReturn(true);
        
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('users')
            ->andReturn(true);
        
        $hasTable = Schema::hasTable('users');
        
        $this->assertTrue($hasTable);
    }
}