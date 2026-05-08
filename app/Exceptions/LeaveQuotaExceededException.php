<?php

namespace App\Exceptions;

use Exception;

class LeaveQuotaExceededException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Annual leave quota exceeded.'
    ) {
        parent::__construct($message);
    }
}
