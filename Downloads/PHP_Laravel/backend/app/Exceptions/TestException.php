<?php

namespace App\Exceptions;

use Exception;

class TestException extends Exception
{
    protected $code = 500;

    public function __construct($message = "Test execution failed", $code = 500)
    {
        parent::__construct($message, $code);
    }
}