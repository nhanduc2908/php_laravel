<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    protected $code = 500;

    public function __construct($message = "Database error occurred", $code = 500)
    {
        parent::__construct($message, $code);
    }
}