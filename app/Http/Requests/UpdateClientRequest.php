<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Person data
            'peoples_id' => ['required', 'integer', 'exists:peoples,peoples_id'],
            'name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'max:120', 'email'],
            'cellphone_number' => ['required', 'string', 'max:14', 'min:14'],

            // Client
            'clients_id' => ['required', 'integer', 'exists:clients,clients_id'],
            'mechanical_workshops_id' => ['sometimes', 'integer', 'exists:mechanical_workshops,mechanical_workshops_id'],

            // Vehicle data
            'vehicle' => ['required', 'array', 'min:1'],
            'vehicle.0.vehicles_id' => ['required', 'integer', 'exists:vehicles,vehicles_id'],
            'vehicle.0.make_id' => ['required', 'integer', 'exists:makes,make_id'],
            'vehicle.0.model_id' => ['required', 'integer', 'exists:models,model_id'],
            'vehicle.0.plates' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'peoples_id.required' => 'El ID de la persona es requerido.',
            'peoples_id.integer' => 'El ID de la persona debe ser un número entero.',
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
            'cellphone_number.max' => 'El número de teléfono debe tener exactamente 14 caracteres.',
            'cellphone_number.min' => 'El número de teléfono debe tener exactamente 14 caracteres.',

            'clients_id.required' => 'El ID del cliente es requerido.',
            'clients_id.integer' => 'El ID del cliente debe ser un número entero.',
            'clients_id.exists' => 'El cliente especificado no existe.',

            'mechanical_workshops_id.integer' => 'El ID del taller debe ser un número entero.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',

            'vehicle.required' => 'Los datos del vehículo son requeridos.',
            'vehicle.array' => 'Los datos del vehículo deben ser un arreglo.',
            'vehicle.min' => 'Se requiere al menos un vehículo.',

            'vehicle.0.vehicles_id.required' => 'El ID del vehículo es requerido.',
            'vehicle.0.vehicles_id.integer' => 'El ID del vehículo debe ser un número entero.',
            'vehicle.0.vehicles_id.exists' => 'El vehículo especificado no existe.',

            'vehicle.0.make_id.required' => 'El ID de la marca es requerido.',
            'vehicle.0.make_id.integer' => 'El ID de la marca debe ser un número entero.',
            'vehicle.0.make_id.exists' => 'La marca especificada no existe.',

            'vehicle.0.model_id.required' => 'El ID del modelo es requerido.',
            'vehicle.0.model_id.integer' => 'El ID del modelo debe ser un número entero.',
            'vehicle.0.model_id.exists' => 'El modelo especificado no existe.',

            'vehicle.0.plates.required' => 'Las placas del vehículo son requeridas.',
            'vehicle.0.plates.string' => 'Las placas deben ser una cadena de texto.',
            'vehicle.0.plates.max' => 'Las placas no pueden exceder 20 caracteres.',
        ];
    }
}
