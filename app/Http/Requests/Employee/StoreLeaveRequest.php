<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'required',
                'string',
                'max:1000',
            ],

            'attachment' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:2048',
            ],
        ];
    }
}
