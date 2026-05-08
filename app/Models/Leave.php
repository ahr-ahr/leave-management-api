<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'days',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'datetime',
            'status' => LeaveStatus::class,
        ];
    }

    /**
     * Leave owner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin approver.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
