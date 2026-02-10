<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'payment_types_id' => 'required|exists:payment_types,payment_types_id',
            'employees_id' => 'required|exists:employees,employees_id',
            'vehicles_id' => 'required|exists:vehicles,vehicles_id',
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'services' => 'required|array',
            'pieces' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_types_id.required' => 'El tipo de pago es requerido.',
            'payment_types_id.exists' => 'El tipo de pago especificado no existe.',

            'employees_id.required' => 'El ID del empleado es requerido.',
            'employees_id.exists' => 'El empleado especificado no existe.',

            'vehicles_id.required' => 'El ID del vehículo es requerido.',
            'vehicles_id.exists' => 'El vehículo especificado no existe.',

            'mechanical_workshops_id.required' => 'El ID del taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',

            'date.required' => 'La fecha es requerida.',
            'date.date' => 'La fecha debe tener un formato válido.',

            'price.required' => 'El precio es requerido.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',

            'services.required' => 'Los servicios son requeridos.',
            'services.array' => 'Los servicios deben ser un arreglo.',

            'pieces.required' => 'Las piezas son requeridas.',
            'pieces.array' => 'Las piezas deben ser un arreglo.',
        ];
    }
}
