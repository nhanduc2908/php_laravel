<?php

namespace App\Services;

use App\Wrappers\ApiWrapper;
use App\Wrappers\CacheWrapper;
use App\Wrappers\LogWrapper;
use App\Wrappers\ValidationWrapper;

class WrapperService
{
    protected $apiWrapper;
    protected $cacheWrapper;
    protected $logWrapper;
    protected $validationWrapper;
    
    public function __construct(
        ApiWrapper $apiWrapper,
        CacheWrapper $cacheWrapper,
        LogWrapper $logWrapper,
        ValidationWrapper $validationWrapper
    ) {
        $this->apiWrapper = $apiWrapper;
        $this->cacheWrapper = $cacheWrapper;
        $this->logWrapper = $logWrapper;
        $this->validationWrapper = $validationWrapper;
    }
    
    public function api()
    {
        return $this->apiWrapper;
    }
    
    public function cache()
    {
        return $this->cacheWrapper;
    }
    
    public function log()
    {
        return $this->logWrapper;
    }
    
    public function validate($data, $rules)
    {
        return $this->validationWrapper->validate($data, $rules);
    }
    
    public function response($data, $message = 'Success', $code = 200)
    {
        return $this->apiWrapper->success($data, $message, $code);
    }
    
    public function error($message, $code = 400, $errors = null)
    {
        return $this->apiWrapper->error($message, $code, $errors);
    }
}