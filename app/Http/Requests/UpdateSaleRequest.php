<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
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
            'services_sales_id' => 'required|exists:services_sales,services_sales_id',
            'payment_types_id' => 'sometimes|exists:payment_types,payment_types_id',
            'employees_id' => 'sometimes|exists:employees,employees_id',
            'vehicles_id' => 'sometimes|exists:vehicles,vehicles_id',
            'mechanical_workshops_id' => 'sometimes|exists:mechanical_workshops,id',
            'date' => 'sometimes|date',
            'price' => 'sometimes|numeric|min:0',
            'services' => 'sometimes|array',
            'pieces' => 'sometimes|array',
        ];
    }

    public function messages(): array
    {
        return [
            'services_sales_id.required' => 'El ID de la venta de servicio es requerido.',
            'services_sales_id.exists' => 'La venta de servicio especificada no existe.',

            'payment_types_id.exists' => 'El tipo de pago especificado no existe.',

            'employees_id.exists' => 'El empleado especificado no existe.',

            'vehicles_id.exists' => 'El vehículo especificado no existe.',

            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',

            'date.date' => 'La fecha debe tener un formato válido.',

            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',

            'services.array' => 'Los servicios deben ser un arreglo.',

            'pieces.array' => 'Las piezas deben ser un arreglo.',
        ];
    }
}
