<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'access_type' => 'required|integer|in:1,2,3',
            'class' => 'nullable|string|max:255',
        ];
    }
}
