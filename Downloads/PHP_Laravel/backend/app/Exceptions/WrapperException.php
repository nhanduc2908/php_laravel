<?php

namespace App\Exceptions;

use Exception;

class WrapperException extends Exception
{
    protected $code = 500;

    public function __construct($message = "Wrapper error occurred", $code = 500)
    {
        parent::__construct($message, $code);
    }
}