<?php

namespace App\Http\Controllers\API\Employee;

use App\DTOs\Leave\CreateLeaveDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\StoreLeaveRequest;
use App\Http\Resources\LeaveResource;
use App\Services\LeaveService;
use App\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    use HasApiResponse;

    public function __construct(
        protected LeaveService $leaveService
    ) {
    }

    /**
     * Store leave request.
     */
    public function store(
        StoreLeaveRequest $request
    ): JsonResponse {
        $leave = $this->leaveService
            ->createLeave(
                $request->user(),
                CreateLeaveDTO::fromRequest($request)
            );

        return $this->successResponse(
            new LeaveResource($leave),
            'Leave request created successfully.',
            201
        );
    }

    /**
     * Get authenticated user leave history.
     */
    public function index(
        Request $request
    ): JsonResponse {
        $leaves = $request->user()
            ->leaves()
            ->latest()
            ->get();

        return $this->successResponse(
            LeaveResource::collection($leaves)
        );
    }
}
