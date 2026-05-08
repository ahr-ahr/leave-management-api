<?php

namespace App\DTOs\Leave;

use App\Http\Requests\Admin\ApproveLeaveRequest;

class ApproveLeaveDTO
{
    /**
     * Create DTO from request.
     */
    public static function fromRequest(
        ApproveLeaveRequest $request
    ): self {
        return new self();
    }
}
