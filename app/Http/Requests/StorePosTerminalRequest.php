<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePosTerminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $store = $this->route('store');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pos_terminals')->where(function ($query) use ($store) {
                    return $query->where('store_id', $store->id);
                })
            ],
            'name' => 'required|string|max:255',
            'printer_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'code.required' => 'The terminal code is required.',
            'code.unique' => 'This terminal code is already assigned to another POS terminal in this specific store.',
            'name.required' => 'The terminal name is required.',
            'printer_name.max' => 'The printer name must not exceed 255 characters.',
        ];
    }
}