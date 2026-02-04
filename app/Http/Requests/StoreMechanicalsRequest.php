<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMechanicalsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
            'users_id' => 'required|int|exists:users,users_id',
            'external_cities_id' => 'required|string',
            'external_states_id' => 'required|string',
            'name' => 'required|string|max:60',
            'cellphone_number' => 'required|string|min:14|max:14',
            'email' => 'required|email|max:120',
            'address' => 'required|string|max:255',
            'google_maps_link' => 'sometimes|string|max:255',
        ];
    }

            /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'users_id.required' => 'El ID del usuario es requerido.',
            'users_id.exists' => 'El ID del usuario no existe.',
            'users_id.int' => 'El ID del usuario debe ser un entero.',

            'external_cities_id.required' => 'La ciudad es requerida.',
            'external_cities_id.string' => 'La ciudad debe ser una cadena de texto.',
            'external_states_id.required' => 'El estado es requerido.',
            'external_states_id.string' => 'La ciudad debe ser una cadena de texto.',

            'name.required' => 'El nombre del taller es requerido.',
            'name.string' => 'El nombre del taller debe ser una cadena de texto.',
            'name.max' => 'El nombre del taller no puede exceder 60 caracteres.',

            'cellphone_number.required' => 'El número de celular es requerido.',
            'cellphone_number.string' => 'El número de celular debe ser una cadena de texto.',
            'cellphone_number.min' => 'El número de celular debe tener al menos 14 caracteres.',
            'cellphone_number.max' => 'El número de celular no puede exceder 14 caracteres.',

            'email.required' => 'El correo electrónico es requerido.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede exceder 120 caracteres.',

            'address.required' => 'La dirección es requerida.',
            'address.string' => 'El la dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no puede exceder 255 caracteres.',

            'google_maps_link.max' => 'El enlace no puede exceder 255 caracteres.',
            'google_maps_link.string' => 'El enlace debe ser una cadena de texto.',
        ];
    }
}
