<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'manager_user_id' => 'nullable|exists:users,id',
            'code' => 'required|string|max:50|unique:stores,code',
            'name' => 'required|string|max:255',
            'phone_line_1' => 'nullable|string|max:20',
            'phone_line_2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address_line_1' => 'string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country_code' => 'nullable|string|max:2',
            'timezone' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'code.required' => 'The store code is required.',
            'code.unique' => 'This store code is already in use. Please choose a unique identifier.',
            'name.required' => 'The store name is required.',
            'manager_user_id.exists' => 'The selected store manager does not exist in the system.',
            'email.email' => 'Please provide a valid email address for the store.',
        ];
    }
}