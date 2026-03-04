<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMechanicalsRequest extends FormRequest
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
            'id' => 'required|int|exists:mechanical_workshops,id',
            'external_cities_id' => 'sometimes|string',
            'external_states_id' => 'sometimes|string',
            'name' => 'sometimes|string|max:60',
            'cellphone_number' => 'sometimes|string|min:10|max:14',
            'email' => 'sometimes|email|max:120',
            'address' => 'sometimes|string|max:255',
            'google_maps_link' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.int' => 'El ID debe ser un número entero.',
            'id.required' => 'El ID del taller mecánico es requerido.',
            'id.exists' => 'El taller mecánico especificado no existe.',

            'external_cities_id.string' => 'El ID de la ciudad debe ser una cadena de texto.',

            'external_states_id.string' => 'El ID del estado debe ser una cadena de texto.',

            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 60 caracteres.',

            'cellphone_number.string' => 'El número de teléfono debe ser una cadena de texto.',
            'cellphone_number.min' => 'El número de teléfono debe tener exactamente 14 caracteres.',
            'cellphone_number.max' => 'El número de teléfono debe tener exactamente 14 caracteres.',

            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede exceder 120 caracteres.',

            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no puede exceder 255 caracteres.',

            'google_maps_link.string' => 'El enlace de Google Maps debe ser una cadena de texto.',
            'google_maps_link.max' => 'El enlace de Google Maps no puede exceder 255 caracteres.',
        ];
    }
}
