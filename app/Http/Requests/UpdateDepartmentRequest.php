<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Access the department being updated via route parameters
        $departmentId = $this->route('department')->id;

        return [
            'responsible_user_id' => 'nullable|exists:users,id',
            'code' => 'required|string|max:50|unique:departments,code,' . $departmentId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código de departamento es obligatorio.',
            'code.unique' => 'Este código de departamento ya está en uso. Por favor, elija un código único.',
            'name.required' => 'El nombre del departamento es obligatorio.',
            'responsible_user_id.exists' => 'El usuario responsable seleccionado no existe en el sistema.',
            'email.email' => 'Por favor, proporcione una dirección de correo electrónico válida para el departamento.',
        ];
    }
}