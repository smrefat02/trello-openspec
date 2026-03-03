<?php

namespace Trello\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:private,public',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The board title is required.',
            'title.max' => 'The board title must not exceed 255 characters.',
            'description.max' => 'The description must not exceed 1000 characters.',
            'visibility.required' => 'Please select a visibility option.',
            'visibility.in' => 'Visibility must be either private or public.',
        ];
    }
}
