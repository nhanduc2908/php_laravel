<?php

namespace App\Exceptions;

use Exception;

class ScanException extends Exception
{
    protected $code = 500;

    public function __construct($message = "Server scan failed", $code = 500)
    {
        parent::__construct($message, $code);
    }
}