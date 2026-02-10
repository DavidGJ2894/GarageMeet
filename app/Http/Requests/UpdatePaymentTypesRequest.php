<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentTypesRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_types_id.required' => 'El ID del tipo de pago es requerido.',
            'payment_types_id.exists' => 'El tipo de pago especificado no existe.',

            'name.required' => 'El nombre del tipo de pago es requerido.',
            'name.string' => 'El nombre del tipo de pago debe ser una cadena de texto.',
            'name.max' => 'El nombre del tipo de pago no puede exceder 255 caracteres.',

            'mechanical_workshops_id.required' => 'El ID del taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',
        ];
    }
}
