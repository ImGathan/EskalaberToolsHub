<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'access_type' => 'required|integer',
            'class' => 'nullable|string|max:255',
        ];
    }
}
