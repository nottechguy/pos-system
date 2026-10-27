<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePosTerminalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $store = $this->route('store');
        $terminal = $this->route('terminal');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pos_terminals')->where(function ($query) use ($store) {
                    return $query->where('store_id', $store->id);
                })->ignore($terminal->id)
            ],
            'name' => 'required|string|max:255',
            'printer_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            'code.required' => 'El código de terminal es obligatorio.',
            'code.unique' => 'This terminal code is already assigned to another POS terminal in this specific store.',
            'name.required' => 'Este código de terminal ya está asignado a otro terminal de punto de venta en esta tienda en particular.',
            'printer_name.max' => 'El nombre de la impresora no debe superar los 255 caracteres.',
        ];
    }
}