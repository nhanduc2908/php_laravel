<?php

namespace App\Wrappers;

class ErrorWrapper
{
    public function handle($exception)
    {
        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
        $message = $exception->getMessage() ?: 'Internal Server Error';
        
        return (new ApiWrapper())->error($message, $code);
    }

    public function report($exception)
    {
        \Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}