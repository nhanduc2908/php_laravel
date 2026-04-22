<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $user;
    protected $admin;
    protected $token;

    /**
     * Setup the test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations for testing
        $this->artisan('migrate:fresh');
        
        // Run seeders
        $this->artisan('db:seed');
        
        // Set up authentication for tests
        $this->withoutExceptionHandling();
    }

    /**
     * Clean up the testing environment
     */
    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    /**
     * Authenticate as a specific user
     */
    protected function actingAsUser($user = null)
    {
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $this->user = $user;
        $this->token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $this->token);
        
        return $this;
    }

    /**
     * Authenticate as admin user
     */
    protected function actingAsAdmin()
    {
        $admin = User::where('role_id', 2)->first();
        if (!$admin) {
            $admin = User::factory()->admin()->create();
        }
        
        $this->admin = $admin;
        $this->token = JWTAuth::fromUser($admin);
        $this->withHeader('Authorization', 'Bearer ' . $this->token);
        
        return $this;
    }

    /**
     * Authenticate as super admin user
     */
    protected function actingAsSuperAdmin()
    {
        $superAdmin = User::where('role_id', 1)->first();
        if (!$superAdmin) {
            $superAdmin = User::factory()->superAdmin()->create();
        }
        
        $this->token = JWTAuth::fromUser($superAdmin);
        $this->withHeader('Authorization', 'Bearer ' . $this->token);
        
        return $this;
    }

    /**
     * Make JSON POST request
     */
    protected function jsonPost($uri, array $data = [], array $headers = [])
    {
        return $this->json('POST', $uri, $data, $headers);
    }

    /**
     * Make JSON GET request
     */
    protected function jsonGet($uri, array $data = [], array $headers = [])
    {
        return $this->json('GET', $uri, $data, $headers);
    }

    /**
     * Make JSON PUT request
     */
    protected function jsonPut($uri, array $data = [], array $headers = [])
    {
        return $this->json('PUT', $uri, $data, $headers);
    }

    /**
     * Make JSON DELETE request
     */
    protected function jsonDelete($uri, array $data = [], array $headers = [])
    {
        return $this->json('DELETE', $uri, $data, $headers);
    }

    /**
     * Assert response structure for API
     */
    protected function assertApiSuccess($response, $expectedData = null)
    {
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'code',
                     'message',
                     'data',
                     'timestamp'
                 ])
                 ->assertJson(['status' => 'success']);
        
        if ($expectedData) {
            $response->assertJson(['data' => $expectedData]);
        }
    }

    /**
     * Assert API error response
     */
    protected function assertApiError($response, $expectedCode = 400, $expectedMessage = null)
    {
        $response->assertStatus($expectedCode)
                 ->assertJsonStructure([
                     'status',
                     'code',
                     'message',
                     'timestamp'
                 ])
                 ->assertJson(['status' => 'error']);
        
        if ($expectedMessage) {
            $response->assertJson(['message' => $expectedMessage]);
        }
    }

    /**
     * Assert validation error response
     */
    protected function assertValidationError($response, $field = null)
    {
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'message',
                     'errors'
                 ]);
        
        if ($field) {
            $response->assertJsonValidationErrors([$field]);
        }
    }

    /**
     * Create test user
     */
    protected function createTestUser($attributes = [])
    {
        return User::factory()->create($attributes);
    }

    /**
     * Create test server
     */
    protected function createTestServer($attributes = [])
    {
        return \App\Models\Server::factory()->create($attributes);
    }

    /**
     * Create test criteria
     */
    protected function createTestCriteria($attributes = [])
    {
        return \App\Models\Criteria::factory()->active()->create($attributes);
    }

    /**
     * Create test assessment file
     */
    protected function createTestFile($attributes = [])
    {
        return \App\Models\AssessmentFile::factory()->create($attributes);
    }

    /**
     * Fake file upload
     */
    protected function fakeUploadFile($name = 'test.pdf', $size = 1024)
    {
        return \Illuminate\Http\UploadedFile::fake()->create($name, $size);
    }

    /**
     * Get sample assessment answers
     */
    protected function getSampleAnswers()
    {
        $criteria = \App\Models\Criteria::take(5)->get();
        $answers = [];
        
        foreach ($criteria as $criterion) {
            $answers[] = [
                'criteria_id' => $criterion->id,
                'value' => 'yes',
                'evidence' => 'Test evidence',
                'note' => 'Test note'
            ];
        }
        
        return $answers;
    }

    /**
     * Generate random string
     */
    protected function randomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
    }

    /**
     * Generate random email
     */
    protected function randomEmail()
    {
        return $this->randomString(8) . '@test.com';
    }

    /**
     * Wait for async jobs to complete
     */
    protected function waitForJobs()
    {
        $this->artisan('queue:work --once');
    }

    /**
     * Clear all cache
     */
    protected function clearCache()
    {
        $this->artisan('cache:clear');
        $this->artisan('config:clear');
        $this->artisan('route:clear');
        $this->artisan('view:clear');
    }

    /**
     * Mock external API service
     */
    protected function mockApiService($serviceName, $method, $returnValue)
    {
        $mock = \Mockery::mock("App\\Services\\{$serviceName}");
        $mock->shouldReceive($method)->andReturn($returnValue);
        $this->app->instance("App\\Services\\{$serviceName}", $mock);
        
        return $mock;
    }
}