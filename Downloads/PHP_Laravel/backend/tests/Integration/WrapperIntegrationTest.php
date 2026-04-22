<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Wrappers\ApiWrapper;
use App\Wrappers\CacheWrapper;
use App\Wrappers\ValidationWrapper;
use App\Wrappers\JwtWrapper;

class WrapperIntegrationTest extends TestCase
{
    protected $apiWrapper;
    protected $cacheWrapper;
    protected $validationWrapper;
    protected $jwtWrapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiWrapper = new ApiWrapper();
        $this->cacheWrapper = new CacheWrapper();
        $this->validationWrapper = new ValidationWrapper();
        $this->jwtWrapper = new JwtWrapper();
    }

    public function test_api_wrapper_formats_response_correctly()
    {
        $data = ['id' => 1, 'name' => 'Test'];
        
        $response = $this->apiWrapper->success($data, 'Success message');
        
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('timestamp', $response);
        $this->assertEquals('success', $response['status']);
        $this->assertEquals(200, $response['code']);
    }

    public function test_cache_wrapper_integration()
    {
        $key = 'integration_test_key';
        $value = 'integration_test_value';
        
        $this->cacheWrapper->set($key, $value, 60);
        $retrieved = $this->cacheWrapper->get($key);
        
        $this->assertEquals($value, $retrieved);
        
        $this->cacheWrapper->delete($key);
        $deleted = $this->cacheWrapper->get($key);
        
        $this->assertNull($deleted);
    }

    public function test_validation_wrapper_with_real_data()
    {
        $data = ['email' => 'invalid'];
        $rules = ['email' => 'required|email'];
        
        $isValid = $this->validationWrapper->validate($data, $rules);
        
        $this->assertFalse($isValid);
        $this->assertArrayHasKey('email', $this->validationWrapper->errors());
    }

    public function test_jwt_wrapper_integration_with_user()
    {
        $user = \App\Models\User::factory()->create();
        
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email
        ];
        
        $token = $this->jwtWrapper->encode($payload, 3600);
        $decoded = $this->jwtWrapper->decode($token);
        
        $this->assertNotNull($token);
        $this->assertNotNull($decoded);
        $this->assertEquals($user->id, $decoded['user_id']);
        $this->assertEquals($user->email, $decoded['email']);
    }

    public function test_multiple_wrappers_work_together()
    {
        // JWT -> Cache -> Validation flow
        $user = \App\Models\User::factory()->create();
        $token = $this->jwtWrapper->encode(['user_id' => $user->id], 3600);
        
        $this->cacheWrapper->set('auth_token', $token, 60);
        $cachedToken = $this->cacheWrapper->get('auth_token');
        
        $decoded = $this->jwtWrapper->decode($cachedToken);
        
        $this->assertEquals($user->id, $decoded['user_id']);
    }
}