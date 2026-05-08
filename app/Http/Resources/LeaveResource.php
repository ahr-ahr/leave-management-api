<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(
        Request $request
    ): array {
        return [
            'id' => $this->id,

            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],

            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),

            'days' => $this->days,

            'reason' => $this->reason,

            'attachment_url' => $this->attachment
                ? Storage::url($this->attachment)
                : null,

            'status' => $this->status->value,

            'approved_by' => $this->approvedBy?->name,

            'approved_at' => $this->approved_at,

            'rejection_reason' => $this->rejection_reason,

            'created_at' => $this->created_at,
        ];
    }
}
