<?php

namespace App\Exceptions;

use Exception;

class ResetPasswordException extends Exception
{
    protected $status;

    public function __construct(string $message = 'Password reset failed.', int $status = 400)
    {
        parent::__construct($message);
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
