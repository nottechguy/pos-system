<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'responsible_user_id' => 'nullable|exists:users,id',
            'code' => 'required|string|max:50|unique:departments,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The department code is required.',
            'code.unique' => 'This department code is already in use. Please choose a unique code.',
            'name.required' => 'The department name is required.',
            'responsible_user_id.exists' => 'The selected responsible user does not exist in the system.',
            'email.email' => 'Please provide a valid email address for the department.',
        ];
    }
}