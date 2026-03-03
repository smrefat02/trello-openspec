<?php

namespace Trello\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCardRequest extends FormRequest
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
            'description' => 'nullable|string|max:2000',
            'list_id' => 'nullable|exists:trello_lists,id',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:todo,in_progress,done',
            'priority' => 'nullable|in:low,medium,high',
            'position' => 'nullable|integer',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The card title is required.',
            'title.max' => 'The card title must not exceed 255 characters.',
            'description.max' => 'The description must not exceed 2000 characters.',
            'list_id.exists' => 'The selected list does not exist.',
            'due_date.date' => 'The due date must be a valid date.',
            'status.in' => 'Status must be one of: todo, in_progress, done.',
            'priority.in' => 'Priority must be one of: low, medium, high.',
            'position.integer' => 'The position must be a valid number.',
        ];
    }
}
