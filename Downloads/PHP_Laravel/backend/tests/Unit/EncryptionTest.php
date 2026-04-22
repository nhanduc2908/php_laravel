<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Wrappers\CryptoWrapper;

class EncryptionTest extends TestCase
{
    protected $crypto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->crypto = new CryptoWrapper();
    }

    public function test_encrypt_and_decrypt()
    {
        $original = 'Sensitive Data 123!@#';
        
        $encrypted = $this->crypto->encrypt($original);
        $decrypted = $this->crypto->decrypt($encrypted);
        
        $this->assertNotEquals($original, $encrypted);
        $this->assertEquals($original, $decrypted);
    }

    public function test_hash_password()
    {
        $password = 'MySecretPassword123';
        
        $hash = $this->crypto->hash($password);
        
        $this->assertNotEquals($password, $hash);
        $this->assertTrue($this->crypto->verify($password, $hash));
        $this->assertFalse($this->crypto->verify('WrongPassword', $hash));
    }

    public function test_different_inputs_produce_different_hashes()
    {
        $hash1 = $this->crypto->hash('password1');
        $hash2 = $this->crypto->hash('password2');
        
        $this->assertNotEquals($hash1, $hash2);
    }
}