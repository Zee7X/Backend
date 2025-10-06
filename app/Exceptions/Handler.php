<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (\App\Exceptions\ResetPasswordException $e, $request) {
            return response()->json([
                'error'   => class_basename($e),
                'message' => $e->getMessage(),
            ], $e->getStatus());
        });

        $this->renderable(function (\App\Exceptions\CategoryException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return redirect()->guest('/login');
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof InvalidCredentialsException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getCode() ?: 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'errors' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof UserRegistrationException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
