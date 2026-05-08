<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedLeaveActionException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Unauthorized leave action.'
    ) {
        parent::__construct($message);
    }
}
