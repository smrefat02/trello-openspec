<?php

namespace Trello\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListRequest extends FormRequest
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
            'board_id' => 'required|exists:boards,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The list title is required.',
            'title.max' => 'The list title must not exceed 255 characters.',
            'board_id.required' => 'The board is required.',
            'board_id.exists' => 'The selected board does not exist.',
        ];
    }
}
