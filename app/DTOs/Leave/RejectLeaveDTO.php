<?php

namespace App\DTOs\Leave;

use App\Http\Requests\Admin\RejectLeaveRequest;

class RejectLeaveDTO
{
    public function __construct(
        public readonly string $rejectionReason,
    ) {
    }

    /**
     * Create DTO from request.
     */
    public static function fromRequest(
        RejectLeaveRequest $request
    ): self {
        return new self(
            rejectionReason: $request->validated(
                'rejection_reason'
            ),
        );
    }
}
