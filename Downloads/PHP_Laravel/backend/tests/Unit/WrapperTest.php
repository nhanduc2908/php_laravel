<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Wrappers\ApiWrapper;
use App\Wrappers\ErrorWrapper;

class WrapperTest extends TestCase
{
    public function test_api_wrapper_success_response()
    {
        $api = new ApiWrapper();
        $data = ['id' => 1, 'name' => 'Test'];
        
        $response = $api->success($data, 'Success message');
        
        $this->assertEquals('success', $response['status']);
        $this->assertEquals(200, $response['code']);
        $this->assertEquals('Success message', $response['message']);
        $this->assertEquals($data, $response['data']);
        $this->assertArrayHasKey('timestamp', $response);
    }

    public function test_api_wrapper_error_response()
    {
        $api = new ApiWrapper();
        
        $response = $api->error('Error message', 400, ['field' => 'Invalid']);
        
        $this->assertEquals('error', $response['status']);
        $this->assertEquals(400, $response['code']);
        $this->assertEquals('Error message', $response['message']);
        $this->assertEquals(['field' => 'Invalid'], $response['errors']);
    }

    public function test_error_wrapper_handles_exception()
    {
        $error = new ErrorWrapper();
        $exception = new \Exception('Test exception', 500);
        
        $response = $error->handle($exception);
        
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('error', $response['status']);
    }
}