<?php

namespace App\DTOs\Leave;

use App\Http\Requests\Employee\StoreLeaveRequest;
use Illuminate\Http\UploadedFile;

class CreateLeaveDTO
{
    public function __construct(
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $reason,
        public readonly UploadedFile $attachment,
    ) {
    }

    /**
     * Create DTO from request.
     */
    public static function fromRequest(
        StoreLeaveRequest $request
    ): self {
        return new self(
            startDate: $request->validated('start_date'),
            endDate: $request->validated('end_date'),
            reason: $request->validated('reason'),
            attachment: $request->file('attachment'),
        );
    }
}
