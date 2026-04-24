<?php

namespace App\Exceptions;

use Exception;

class FileException extends Exception
{
    protected $code = 400;

    public function __construct($message = "File operation failed", $code = 400)
    {
        parent::__construct($message, $code);
    }
}