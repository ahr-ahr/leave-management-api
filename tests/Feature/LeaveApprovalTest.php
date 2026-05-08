<?php

namespace Tests\Feature;

use App\Enums\LeaveStatus;
use App\Enums\UserRole;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveApprovalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin can approve leave request.
     */
    public function test_admin_can_approve_leave(): void
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
            'reason' => 'Family event',
            'attachment' => 'attachments/test.pdf',
            'status' => LeaveStatus::PENDING,
        ]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->patchJson(
                "/api/admin/leaves/{$leave->id}/approve"
            );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Leave approved successfully.',
            ]);

        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => LeaveStatus::APPROVED->value,
            'approved_by' => $admin->id,
        ]);
    }

    /**
     * Admin can reject leave request.
     */
    public function test_admin_can_reject_leave(): void
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
            'status' => LeaveStatus::PENDING,
        ]);

        $response = $this
            ->actingAs($admin, 'sanctum')
            ->patchJson(
                "/api/admin/leaves/{$leave->id}/reject",
                [
                    'rejection_reason' => 'Insufficient team coverage',
                ]
            );

        $response
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Leave rejected successfully.',
            ]);

        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => LeaveStatus::REJECTED->value,
        ]);
    }

    /**
     * Employee cannot approve leave request.
     */
    public function test_employee_cannot_approve_leave(): void
    {
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
            'status' => LeaveStatus::PENDING,
        ]);

        $response = $this
            ->actingAs($employee, 'sanctum')
            ->patchJson(
                "/api/admin/leaves/{$leave->id}/approve"
            );

        $response->assertForbidden();
    }
}
