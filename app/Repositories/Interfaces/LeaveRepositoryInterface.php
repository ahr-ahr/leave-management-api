<?php

namespace App\Repositories\Interfaces;

use App\Models\Leave;
use Illuminate\Database\Eloquent\Collection;

interface LeaveRepositoryInterface
    extends BaseRepositoryInterface
{
    /**
     * Get all leaves by user.
     */
    public function getByUser(
        int $userId
    ): Collection;

    /**
     * Get total approved leave days this year.
     */
    public function getUsedLeaveDays(
        int $userId,
        int $year
    ): int;

    /**
     * Check overlapping leave request.
     */
    public function hasOverlapLeave(
        int $userId,
        string $startDate,
        string $endDate
    ): bool;

    /**
     * Get pending leaves.
     */
    public function getPendingLeaves(): Collection;

    /**
     * Find leave by id.
     */
    public function findById(
        int|string $id
    ): ?Leave;
}
