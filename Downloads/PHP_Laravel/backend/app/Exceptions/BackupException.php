<?php

namespace App\Exceptions;

use Exception;

class BackupException extends Exception
{
    protected $code = 500;

    public function __construct($message = "Backup operation failed", $code = 500)
    {
        parent::__construct($message, $code);
    }
}