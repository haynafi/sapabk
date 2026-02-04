<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentIntakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public route
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:50'],
            // Strict photo validation: only allow specific image types, max 2MB
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
            'biodata' => ['nullable', 'array'],
            'biodata.*' => ['nullable', 'string', 'max:1000'],
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Student name is required.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.max' => 'The photo must not be larger than 2MB.',
        ];
    }
}
