<?php

namespace App\Repositories;

use App\Enums\LeaveStatus;
use App\Models\Leave;
use App\Repositories\Interfaces\LeaveRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaveRepository implements LeaveRepositoryInterface
{
    /**
     * Get all leaves.
     */
    public function all(): Collection
    {
        return Leave::all();
    }

    /**
     * Find leave by id.
     */
    public function findById(
        int|string $id
    ): ?Leave {
        return Leave::find($id);
    }

    /**
     * Create new leave.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        array $data
    ): Leave {
        return Leave::create($data);
    }

    /**
     * Update leave.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        int|string $id,
        array $data
    ): bool {
        $leave = $this->findById($id);

        return $leave
            ? $leave->update($data)
            : false;
    }

    /**
     * Delete leave.
     */
    public function delete(
        int|string $id
    ): bool {
        $leave = $this->findById($id);

        return $leave
            ? $leave->delete()
            : false;
    }

    /**
     * Get leaves by user.
     */
    public function getByUser(
        int $userId
    ): Collection {
        return Leave::query()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * Get used approved leave days this year.
     */
    public function getUsedLeaveDays(
        int $userId,
        int $year
    ): int {
        return Leave::query()
            ->where('user_id', $userId)
            ->where('status', LeaveStatus::APPROVED)
            ->whereYear('start_date', $year)
            ->sum('days');
    }

    /**
     * Check overlapping leave.
     */
    public function hasOverlapLeave(
        int $userId,
        string $startDate,
        string $endDate
    ): bool {
        return Leave::query()
            ->where('user_id', $userId)
            ->whereIn('status', [
                LeaveStatus::PENDING,
                LeaveStatus::APPROVED,
            ])
            ->where(function ($query) use ($startDate, $endDate) {
                $query
                    ->whereBetween(
                        'start_date',
                        [$startDate, $endDate]
                    )
                    ->orWhereBetween(
                        'end_date',
                        [$startDate, $endDate]
                    );
            })
            ->exists();
    }

    /**
     * Get pending leaves.
     */
    public function getPendingLeaves(): Collection
    {
        return Leave::query()
            ->where('status', LeaveStatus::PENDING)
            ->latest()
            ->get();
    }
}
