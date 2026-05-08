<?php

namespace App\Services;

use App\DTOs\Leave\{CreateLeaveDTO, ApproveLeaveDTO, RejectLeaveDTO};
use App\Enums\LeaveStatus;
use App\Exceptions\{LeaveOverlapException, LeaveQuotaExceededException};
use App\Models\{Leave, User};
use App\Repositories\Interfaces\LeaveRepositoryInterface;
use Carbon\Carbon;
use App\Exceptions\UnauthorizedLeaveActionException;

class LeaveService
{
    public function __construct(
        protected LeaveRepositoryInterface $leaveRepository
    ) {
    }

    /**
     * Create leave request.
     */
    public function createLeave(
        User $user,
        CreateLeaveDTO $dto
    ): Leave {
        $startDate = Carbon::parse(
            $dto->startDate
        );

        $endDate = Carbon::parse(
            $dto->endDate
        );

        /**
         * Calculate leave days.
         */
        $days = $startDate
            ->diffInDays($endDate) + 1;

        /**
         * Check annual quota.
         */
        $usedDays = $this->leaveRepository
            ->getUsedLeaveDays(
                $user->id,
                now()->year
            );

        if (($usedDays + $days) > 12) {
            throw new LeaveQuotaExceededException();
        }

        /**
         * Check overlapping leave.
         */
        $hasOverlap = $this->leaveRepository
            ->hasOverlapLeave(
                $user->id,
                $dto->startDate,
                $dto->endDate
            );

        if ($hasOverlap) {
            throw new LeaveOverlapException();
        }

        /**
         * Store attachment.
         */
        $attachmentPath = $dto->attachment
            ->store(
                'attachments',
                'public'
            );

        /**
         * Create leave request.
         */
        return $this->leaveRepository->create([
            'user_id' => $user->id,

            'start_date' => $dto->startDate,

            'end_date' => $dto->endDate,

            'days' => $days,

            'reason' => $dto->reason,

            'attachment' => $attachmentPath,

            'status' => LeaveStatus::PENDING,
        ]);
    }

    /**
     * Approve leave request.
     */
    public function approveLeave(
        int $leaveId,
        User $admin,
        ApproveLeaveDTO $dto
    ): Leave {
        $leave = $this->leaveRepository
            ->findById($leaveId);

        if (
            !$leave ||
            $leave->status !== LeaveStatus::PENDING
        ) {
            throw new UnauthorizedLeaveActionException(
                'Leave request cannot be approved.'
            );
        }

        $this->leaveRepository->update(
            $leave->id,
            [
                'status' => LeaveStatus::APPROVED,

                'approved_by' => $admin->id,

                'approved_at' => now(),
            ]
        );

        return $this->leaveRepository
            ->findById($leave->id);
    }

    /**
     * Reject leave request.
     */
    public function rejectLeave(
        int $leaveId,
        User $admin,
        RejectLeaveDTO $dto
    ): Leave {
        $leave = $this->leaveRepository
            ->findById($leaveId);

        if (
            !$leave ||
            $leave->status !== LeaveStatus::PENDING
        ) {
            throw new UnauthorizedLeaveActionException(
                'Leave request cannot be rejected.'
            );
        }

        $this->leaveRepository->update(
            $leave->id,
            [
                'status' => LeaveStatus::REJECTED,

                'approved_by' => $admin->id,

                'approved_at' => now(),

                'rejection_reason' => $dto->rejectionReason,
            ]
        );

        return $this->leaveRepository
            ->findById($leave->id);
    }
}
