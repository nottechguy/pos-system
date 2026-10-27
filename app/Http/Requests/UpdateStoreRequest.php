<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        $storeId = $this->route('store')->id;

        return [
            'manager_user_id' => 'nullable|exists:users,id',
            'code' => 'required|string|max:50|unique:stores,code,' . $storeId,
            'name' => 'required|string|max:255',
            'phone_line_1' => 'nullable|string|max:20',
            'phone_line_2' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address_line_1' => 'nullable|string|max:255',
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
            'code.required' => 'El código de la tienda es obligatorio.',
            'code.unique' => 'Este código de tienda ya está en uso. Por favor, elija un identificador único.',
            'name.required' => 'El nombre de la tienda es obligatorio.',
            'manager_user_id.exists' => 'El gerente de tienda seleccionado no existe en el sistema.',
            'email.email' => 'Por favor, proporcione una dirección de correo electrónico válida para la tienda.',
        ];
    }
}