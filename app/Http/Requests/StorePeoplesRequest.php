<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeoplesRequest extends FormRequest
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
            'name' => 'required|string|max:60',
            'last_name' => 'required|string|max:60',
            'email' => 'required|string|max:120|email',
            'cellphone_number' => 'required|string|max:14|min:14',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 60 caracteres.',

            'last_name.required' => 'El apellido es requerido.',
            'last_name.string' => 'El apellido debe ser una cadena de texto.',
            'last_name.max' => 'El apellido no puede exceder 60 caracteres.',

            'email.required' => 'El correo electronico es requerido.',
            'email.string' => 'El correo electronico debe ser una cadena de texto.',
            'email.email' => 'El correo electronico debe tener un formato valido.',
            'email.max' => 'El correo electronico no puede exceder 120 caracteres.',

            'cellphone_number.required' => 'El numero de telefono es requerido.',
            'cellphone_number.string' => 'El numero de telefono debe ser una cadena de texto.',
            'cellphone_number.min' => 'El numero de telefono debe tener exactamente 14 caracteres.',
            'cellphone_number.max' => 'El numero de telefono debe tener exactamente 14 caracteres.',
        ];
    }
}
