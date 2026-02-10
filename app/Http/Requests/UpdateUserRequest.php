<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {


        return [
            'name' => 'sometimes|string|max:60',
            'last_name' => 'sometimes|string|max:90',
            //'email' => 'sometimes|string|email|max:255|unique:users,email,' . $userId . ',users_id',
            'password' => 'sometimes|string|min:4|confirmed',
            'current_password' => 'required_with:password|string',
            'type_users_id' => 'sometimes|int|exists:type_users,type_users_id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 60 caracteres.',

            'last_name.string' => 'El apellido debe ser una cadena de texto.',
            'last_name.max' => 'El apellido no puede exceder 90 caracteres.',

            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',

            'current_password.required_with' => 'La contraseña actual es requerida para cambiar la contraseña.',
            'current_password.string' => 'La contraseña actual debe ser una cadena de texto.',

            'type_users_id.int' => 'El tipo de usuario debe ser un número entero.',
            'type_users_id.exists' => 'El tipo de usuario seleccionado no existe.',
        ];
    }
}
