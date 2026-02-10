<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeoplesRequest extends FormRequest
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
            'peoples_id' => 'required|int|exists:peoples,peoples_id',
            'name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|string|max:120|email',
            'cellphone_number' => 'required|string|max:14|min:14',
        ];
    }

    public function messages(): array
    {
        return [
            'peoples_id.required' => 'El ID de la persona es requerido.',
            'peoples_id.int' => 'El ID de la persona debe ser un número entero.',
            'peoples_id.exists' => 'La persona especificada no existe.',

            'name.required' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 60 caracteres.',

            'last_name.required' => 'El apellido es requerido.',
            'last_name.string' => 'El apellido debe ser una cadena de texto.',
            'last_name.max' => 'El apellido no puede exceder 60 caracteres.',

            'email.required' => 'El correo electrónico es requerido.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede exceder 120 caracteres.',

            'cellphone_number.required' => 'El número de teléfono es requerido.',
            'cellphone_number.string' => 'El número de teléfono debe ser una cadena de texto.',
            'cellphone_number.min' => 'El número de teléfono debe tener exactamente 14 caracteres.',
            'cellphone_number.max' => 'El número de teléfono debe tener exactamente 14 caracteres.',
        ];
    }
}
