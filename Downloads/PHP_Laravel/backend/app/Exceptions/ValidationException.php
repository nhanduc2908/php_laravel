<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $errors;
    protected $code = 400;

    public function __construct($message = "Validation failed", $errors = [], $code = 400)
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}