<?php

namespace Tests\Unit;

use App\DTOs\Leave\ApproveLeaveDTO;
use App\DTOs\Leave\CreateLeaveDTO;
use App\Enums\LeaveStatus;
use App\Enums\UserRole;
use App\Exceptions\LeaveOverlapException;
use App\Exceptions\LeaveQuotaExceededException;
use App\Exceptions\UnauthorizedLeaveActionException;
use App\Models\Leave;
use App\Models\User;
use App\Repositories\LeaveRepository;
use App\Services\LeaveService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LeaveServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LeaveService $leaveService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->leaveService = new LeaveService(
            new LeaveRepository()
        );
    }

    /**
     * Leave quota exceeded.
     */
    public function test_leave_quota_exceeded(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        Leave::query()->create([
            'user_id' => $user->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-12',
            'days' => 12,
            'reason' => 'Vacation',
            'attachment' => 'attachments/test.pdf',
            'status' => LeaveStatus::APPROVED,
        ]);

        $dto = new CreateLeaveDTO(
            startDate: '2026-06-01',
            endDate: '2026-06-05',
            reason: 'Another leave',
            attachment: UploadedFile::fake()
                ->create('document.pdf', 100),
        );

        $this->expectException(
            LeaveQuotaExceededException::class
        );

        $this->leaveService
            ->createLeave($user, $dto);
    }

    /**
     * Leave overlap detection.
     */
    public function test_leave_overlap_detection(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        Leave::query()->create([
            'user_id' => $user->id,
            'start_date' => '2026-05-10',
            'end_date' => '2026-05-12',
            'days' => 3,
            'reason' => 'Vacation',
            'attachment' => 'attachments/test.pdf',
            'status' => LeaveStatus::PENDING,
        ]);

        $dto = new CreateLeaveDTO(
            startDate: '2026-05-11',
            endDate: '2026-05-13',
            reason: 'Overlap leave',
            attachment: UploadedFile::fake()
                ->create('document.pdf', 100),
        );

        $this->expectException(
            LeaveOverlapException::class
        );

        $this->leaveService
            ->createLeave($user, $dto);
    }

    /**
     * Cannot approve non-pending leave.
     */
    public function test_cannot_approve_non_pending_leave(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
        ]);

        $employee = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $leave = Leave::query()->create([
            'user_id' => $employee->id,
            'start_date' => '2026-05-10',
            'end_date' => '2026-05-12',
            'days' => 3,
            'reason' => 'Vacation',
            'attachment' => 'attachments/test.pdf',
            'status' => LeaveStatus::APPROVED,
        ]);

        $dto = new ApproveLeaveDTO();

        $this->expectException(
            UnauthorizedLeaveActionException::class
        );

        $this->leaveService
            ->approveLeave(
                $leave->id,
                $admin,
                $dto
            );
    }
}
