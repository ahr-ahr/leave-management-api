<?php

namespace Tests\Feature;

use App\Enums\LeaveStatus;
use App\Enums\UserRole;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Employee can create leave request.
     */
    public function test_employee_can_create_leave_request(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::EMPLOYEE,
        ]);

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/employee/leaves', [
                'start_date' => now()
                    ->addDay()
                    ->format('Y-m-d'),

                'end_date' => now()
                    ->addDays(3)
                    ->format('Y-m-d'),

                'reason' => 'Family event',

                'attachment' => UploadedFile::fake()
                    ->create('document.pdf', 100),
            ]);

        $response
            ->assertCreated()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('leaves', [
            'user_id' => $user->id,
            'status' => LeaveStatus::PENDING->value,
        ]);
    }

    /**
     * Employee cannot create overlapping leave.
     */
    public function test_employee_cannot_create_overlap_leave(): void
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

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/employee/leaves', [
                'start_date' => '2026-05-11',
                'end_date' => '2026-05-13',
                'reason' => 'Another leave',

                'attachment' => UploadedFile::fake()
                    ->create('document.pdf', 100),
            ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Guest cannot create leave request.
     */
    public function test_guest_cannot_create_leave_request(): void
    {
        $response = $this->postJson(
            '/api/employee/leaves',
            []
        );

        $response->assertUnauthorized();
    }
}
