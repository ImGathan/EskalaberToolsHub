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
        $currentYear = date('Y');
        $currentMonth = date('n');
        $maxYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $minYear = $maxYear - 2; 

        return [
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'department_id' => 'required|exists:departments,id',
            'class' => 'nullable|string|max:255',
            'years_in' => [
                'nullable',
                'integer',
                'min:'.$minYear,
                'max:'.$maxYear,
            ],
        ];
    }
}
