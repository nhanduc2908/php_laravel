<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Wrappers\JwtWrapper;
use App\Models\User;

class JWTTest extends TestCase
{
    protected $jwt;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jwt = new JwtWrapper();
    }

    public function test_encode_and_decode_token()
    {
        $payload = ['user_id' => 1, 'email' => 'test@example.com'];
        
        $token = $this->jwt->encode($payload, 3600);
        $decoded = $this->jwt->decode($token);
        
        $this->assertNotNull($token);
        $this->assertNotNull($decoded);
        $this->assertEquals($payload['user_id'], $decoded['user_id']);
        $this->assertEquals($payload['email'], $decoded['email']);
    }

    public function test_token_has_expiration()
    {
        $payload = ['user_id' => 1];
        
        $token = $this->jwt->encode($payload, 1); // 1 second TTL
        sleep(2);
        
        $decoded = $this->jwt->decode($token);
        
        $this->assertNull($decoded);
    }

    public function test_refresh_token()
    {
        $payload = ['user_id' => 1];
        $token = $this->jwt->encode($payload, 3600);
        
        $newToken = $this->jwt->refresh($token);
        
        $this->assertNotNull($newToken);
        $this->assertNotEquals($token, $newToken);
    }

    public function test_invalid_token_returns_null()
    {
        $decoded = $this->jwt->decode('invalid.token.here');
        
        $this->assertNull($decoded);
    }
}