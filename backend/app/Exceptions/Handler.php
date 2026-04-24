<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends ExceptionHandler
{
    protected $levels = [];
    protected $dontReport = [];
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log exception
        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            // Validation Exception
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 400);
            }

            // Authentication Exception
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'status' => 'error',
                    'code' => 401,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // Permission Exception
            if ($e instanceof AccessDeniedHttpException || $e instanceof PermissionException) {
                return response()->json([
                    'status' => 'error',
                    'code' => 403,
                    'message' => 'Forbidden - You don\'t have permission'
                ], 403);
            }

            // Not Found Exception
            if ($e instanceof NotFoundHttpException || $e instanceof NotFoundException) {
                return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Resource not found'
                ], 404);
            }

            // Database Exception
            if ($e instanceof DatabaseException) {
                return response()->json([
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Database error occurred'
                ], 500);
            }
        }

        return parent::render($request, $e);
    }
}