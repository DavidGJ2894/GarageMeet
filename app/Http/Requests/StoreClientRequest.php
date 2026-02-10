<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Person data
            'name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'string', 'max:120', 'email'],
            'cellphone_number' => ['required', 'string', 'max:14', 'min:14'],

            // Workshop
            'mechanicals_id' => ['required', 'integer', 'exists:mechanical_workshops,mechanical_workshops_id'],

            // Vehicle data
            'vehicle' => ['required', 'array', 'min:1'],
            'vehicle.0.make_id' => ['required', 'integer', 'exists:makes,make_id'],
            'vehicle.0.model_id' => ['required', 'integer', 'exists:models,model_id'],
            'vehicle.0.plates' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle.required' => 'Se requiere al menos un vehiulo.',
            'vehicle.0.make_id.required' => 'El ID de la marca es requerido.',
            'vehicle.0.model_id.required' => 'El ID del modelo es requerido.',
            'vehicle.0.plates.required' => 'Las placas del vehiulo son requeridas.',
            'mechanicals_id.required' => 'El ID del taller es requerido.',
            'mechanicals_id.exists' => 'El taller especificado no existe.',
        ];
    }
}
