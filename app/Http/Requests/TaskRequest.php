<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'project_id' => ['required','exists:projects,id'],
            'title' => ['required','string','max:255'],
            'details' => ['nullable','string'],
            'priority' => ['required', Rule::in(['low','medium','high'])],
            'is_completed' => ['sometimes','boolean'],
            'assignees' => ['sometimes','array'],
            'assignees.*' => ['integer','exists:users,id'],
        ];
    }
}
