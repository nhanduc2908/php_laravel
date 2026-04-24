<?php

namespace App\Exceptions;

use Exception;

class PermissionException extends Exception
{
    protected $code = 403;

    public function __construct($message = "You don't have permission to access this resource", $code = 403)
    {
        parent::__construct($message, $code);
    }
}