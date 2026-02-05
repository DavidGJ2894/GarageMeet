<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Request validation for creating appointment request.
 *
 * Validates client information and workshop selection for new appointments.
 */
class StoreAppointmentRequest extends FormRequest
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
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'description' => 'required|string|max:1000'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'mechanical_workshops_id.required' => 'El taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller seleccionado no existe.',

            'client_name.required' => 'El nombre del cliente es requerido.',
            'client_name.string' => 'El nombre del cliente debe ser una cadena de texto.',
            'client_name.max' => 'El nombre del cliente no puede exceder 255 caracteres.',

            'client_email.required' => 'El email del cliente es requerido.',
            'client_email.string' => 'El email del cliente debe ser una cadena de texto.',
            'client_email.email' => 'El email debe tener un formato válido.',

            'client_phone.required' => 'El teléfono del cliente es requerido.',
            'client_phone.string' => 'El teléfono del cliente debe ser una cadena de texto.',
            'client_phone.max' => 'El teléfono del cliente no puede exceder 20 caracteres.',

            'description.required' => 'La descripción del problema es requerida.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede exceder 1000 caracteres.',
        ];
    }
}
