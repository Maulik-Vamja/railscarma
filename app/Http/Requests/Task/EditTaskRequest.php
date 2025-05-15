<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class EditTaskRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'string'],
            'status' => ['required', 'string'],
            'due_date' => ['nullable', 'date'],
            'url' => ['nullable', 'url'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
        ];
    }
}
