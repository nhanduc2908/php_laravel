<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $code = 404;

    public function __construct($message = "Resource not found", $code = 404)
    {
        parent::__construct($message, $code);
    }
}