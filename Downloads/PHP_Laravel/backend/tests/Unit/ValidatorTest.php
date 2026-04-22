<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Wrappers\ValidationWrapper;

class ValidatorTest extends TestCase
{
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ValidationWrapper();
    }

    public function test_required_validation()
    {
        $data = ['name' => ''];
        $rules = ['name' => 'required'];
        
        $isValid = $this->validator->validate($data, $rules);
        
        $this->assertFalse($isValid);
        $this->assertArrayHasKey('name', $this->validator->errors());
    }

    public function test_email_validation()
    {
        $data = ['email' => 'invalid-email'];
        $rules = ['email' => 'email'];
        
        $isValid = $this->validator->validate($data, $rules);
        
        $this->assertFalse($isValid);
    }

    public function test_min_length_validation()
    {
        $data = ['password' => '123'];
        $rules = ['password' => 'min:6'];
        
        $isValid = $this->validator->validate($data, $rules);
        
        $this->assertFalse($isValid);
    }

    public function test_valid_data_passes()
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $rules = ['name' => 'required', 'email' => 'email'];
        
        $isValid = $this->validator->validate($data, $rules);
        
        $this->assertTrue($isValid);
    }
}