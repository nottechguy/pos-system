<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CloseShiftRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // count_lines is an array of denomination_id => quantity
            'count_lines' => ['required', 'array', 'min:1'],
            'count_lines.*.denomination_id' => ['required', 'integer', 'exists:cash_denominations,id'],
            'count_lines.*.quantity' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}