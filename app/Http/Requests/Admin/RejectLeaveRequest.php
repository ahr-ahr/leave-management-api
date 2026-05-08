<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }
}
