<?php

namespace App\Exceptions;

use Exception;

class AuthException extends Exception
{
    protected $code = 401;

    public function __construct($message = "Authentication failed", $code = 401)
    {
        parent::__construct($message, $code);
    }
}