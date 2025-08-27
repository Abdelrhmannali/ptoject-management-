<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'status' => ['required', Rule::in(['planned','active','done'])],
            'due_date' => ['nullable','date'],
        ];
    }
}
