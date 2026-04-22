<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $code = 500;

    public function __construct($message = "External API call failed", $code = 500)
    {
        parent::__construct($message, $code);
    }
}