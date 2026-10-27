<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'nullable|exists:business_categories,id',
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'tax_id' => 'required|string|max:20',
            'bio' => 'nullable|string',
            'phone_line_1' => 'nullable|string|max:20',
            'phone_line_2' => 'nullable|string|max:20',
            'email' => ['nullable', 'email', 'max:255'],
            'website' => 'nullable|url|max:255',
            'currency_code' => 'nullable|string|max:3',
            'timezone' => 'nullable|string|max:50',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function attributes(): array {
        return [
            'name' => __('messages.validations.business.form_fields.store_name'),
            'legal_name' => __('messages.validations.business.form_fields.legal_name'),
            'tax_id' => __('messages.validations.business.form_fields.rfc'),
            'bio' => __('messages.validations.business.form_fields.bio'),
            'phone_line_1' => __('messages.validations.business.form_fields.phone_line_1'),
            'phone_line_2' => __('messages.validations.business.form_fields.phone_line_2'),
            'category_id' => __('messages.validations.business.form_fields.store_category'),
            'website' => __('messages.validations.business.form_fields.website'),
            'logo_path' => __('messages.validations.business.form_fields.logo')
        ];
    }
}