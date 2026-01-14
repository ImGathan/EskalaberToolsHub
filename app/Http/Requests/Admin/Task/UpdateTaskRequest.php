<?php

namespace App\Http\Requests\Admin\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'task_category_id' => 'required|exists:task_categories,id',
            'task_date' => 'required|date',
            'status' => 'required|integer',
            'description' => 'nullable|string',
        ];
    }
}
