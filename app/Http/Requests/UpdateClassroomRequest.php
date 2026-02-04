<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('classroom')->teacher_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'quiz_template_id' => ['nullable', 'exists:quiz_templates,id'],
        ];
    }
}
