<?php

namespace App\Http\Controllers\API\Admin;

use App\DTOs\Leave\ApproveLeaveDTO;
use App\DTOs\Leave\RejectLeaveDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveLeaveRequest;
use App\Http\Requests\Admin\RejectLeaveRequest;
use App\Http\Resources\LeaveResource;
use App\Repositories\Interfaces\LeaveRepositoryInterface;
use App\Services\LeaveService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;

class LeaveApprovalController extends Controller
{
    use HasApiResponse;

    public function __construct(
        protected LeaveService $leaveService,
        protected LeaveRepositoryInterface $leaveRepository
    ) {
    }

    /**
     * Get pending leave requests.
     */
    public function pending(): JsonResponse
    {
        $leaves = $this->leaveRepository
            ->getPendingLeaves();

        return $this->successResponse(
            LeaveResource::collection($leaves)
        );
    }

    /**
     * Approve leave request.
     */
    public function approve(
        ApproveLeaveRequest $request,
        int $id
    ): JsonResponse {
        $leave = $this->leaveService
            ->approveLeave(
                $id,
                $request->user(),
                ApproveLeaveDTO::fromRequest($request)
            );

        return $this->successResponse(
            new LeaveResource($leave),
            'Leave approved successfully.'
        );
    }

    /**
     * Reject leave request.
     */
    public function reject(
        RejectLeaveRequest $request,
        int $id
    ): JsonResponse {
        $leave = $this->leaveService
            ->rejectLeave(
                $id,
                $request->user(),
                RejectLeaveDTO::fromRequest($request)
            );

        return $this->successResponse(
            new LeaveResource($leave),
            'Leave rejected successfully.'
        );
    }
}
