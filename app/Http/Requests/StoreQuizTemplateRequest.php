<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['required', 'string'],
            'questions.*.type' => ['required', 'string', 'in:text,essay,multiple_choice,boolean'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.required' => ['required', 'boolean'],
            'questions.*.options' => ['required_if:questions.*.type,multiple_choice', 'array', 'min:2'],
            'questions.*.options.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.required' => 'At least one question is required.',
            'questions.*.question.required' => 'Each question must have a question text.',
            'questions.*.type.required' => 'Each question must have a type.',
            'questions.*.type.in' => 'Question type must be text, essay, multiple_choice, or boolean.',
            'questions.*.options.required_if' => 'Multiple choice questions must have options.',
            'questions.*.options.min' => 'Multiple choice questions must have at least 2 options.',
        ];
    }
}
