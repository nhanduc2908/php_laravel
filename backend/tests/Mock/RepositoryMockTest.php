<?php

namespace Tests\Mock;

use Tests\TestCase;
use App\Repositories\UserRepository;
use App\Repositories\ServerRepository;
use App\Models\User;
use App\Models\Server;
use Mockery;

class RepositoryMockTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_user_repository_mock_find_by_email()
    {
        $mockRepo = Mockery::mock(UserRepository::class);
        $expectedUser = User::factory()->make(['email' => 'test@example.com', 'id' => 1]);
        
        $mockRepo->shouldReceive('findByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($expectedUser);
        
        $user = $mockRepo->findByEmail('test@example.com');
        
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals(1, $user->id);
    }

    public function test_user_repository_mock_get_by_role()
    {
        $mockRepo = Mockery::mock(UserRepository::class);
        $expectedUsers = User::factory()->count(3)->make(['role_id' => 2]);
        
        $mockRepo->shouldReceive('getByRole')
            ->once()
            ->with(2, 15)
            ->andReturn($expectedUsers);
        
        $users = $mockRepo->getByRole(2, 15);
        
        $this->assertCount(3, $users);
        foreach ($users as $user) {
            $this->assertEquals(2, $user->role_id);
        }
    }

    public function test_server_repository_mock_get_by_status()
    {
        $mockRepo = Mockery::mock(ServerRepository::class);
        $expectedServers = Server::factory()->count(5)->make(['status' => 'active']);
        
        $mockRepo->shouldReceive('getByStatus')
            ->once()
            ->with('active', 15)
            ->andReturn($expectedServers);
        
        $servers = $mockRepo->getByStatus('active', 15);
        
        $this->assertCount(5, $servers);
        foreach ($servers as $server) {
            $this->assertEquals('active', $server->status);
        }
    }

    public function test_repository_mock_with_pagination()
    {
        $mockRepo = Mockery::mock(UserRepository::class);
        
        $mockRepo->shouldReceive('paginate')
            ->once()
            ->with(15)
            ->andReturn((object)[
                'data' => User::factory()->count(15)->make(),
                'total' => 50,
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 4
            ]);
        
        $result = $mockRepo->paginate(15);
        
        $this->assertCount(15, $result->data);
        $this->assertEquals(50, $result->total);
        $this->assertEquals(4, $result->last_page);
    }

    public function test_repository_mock_with_search()
    {
        $mockRepo = Mockery::mock(UserRepository::class);
        $keyword = 'john';
        
        $mockRepo->shouldReceive('search')
            ->once()
            ->with($keyword, 15)
            ->andReturn(User::factory()->count(2)->make(['name' => 'John Doe']));
        
        $results = $mockRepo->search($keyword, 15);
        
        $this->assertCount(2, $results);
        foreach ($results as $user) {
            $this->assertStringContainsString('John', $user->name);
        }
    }
}