<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessSetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Assuming authorization is handled by middleware
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'tax_id' => 'required|string|max:20', // RFC
            'phone_line_1' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.validations.business.name_required'),
            'tax_id.required' => __('messages.validations.business.rfc_required'),
            'category_id.exists' => __('messages.validations.business.category_id_invalid'),
            'email.email' => __('messages.validations.business.email_valid'),
            'email.max'   => 'The email address is too long.',
            'email'       => __('messages.validations.business.email_valid'), // Absolute fallback for the email field
            'website.url' => 'Please provide a valid URL (e.g., https://example.com).',
            'logo_path.image' => 'The uploaded file must be an image.',
            'logo_path.mimes' => 'The logo must be a file of type: jpeg, png, jpg.',
            'logo_path.max' => 'The logo may not be greater than 2MB.',
        ];
    }
}