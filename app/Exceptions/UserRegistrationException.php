<?php

namespace App\Exceptions;

use Exception;

class UserRegistrationException extends Exception
{
    protected $message;

    public function __construct($message = "User registration failed")
    {
        parent::__construct($message);
    }
}
