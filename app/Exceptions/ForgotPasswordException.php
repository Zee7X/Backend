<?php

namespace App\Exceptions;

use Exception;

class ForgotPasswordException extends Exception
{
    protected $status;

    public function __construct(string $message = 'Password reset failed.', int $status = 400)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public function render($request)
    {
        return response()->json([
            'error'   => 'ForgotPasswordException',
            'message' => $this->getMessage(),
        ], $this->status);
    }
}
