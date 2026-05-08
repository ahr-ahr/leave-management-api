<?php

namespace App\Exceptions;

use Exception;

class LeaveOverlapException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'Leave request overlaps with existing leave.'
    ) {
        parent::__construct($message);
    }
}
