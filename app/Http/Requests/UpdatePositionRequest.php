<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'positions_id' => 'required|exists:positions,positions_id',
            'name' => 'required|string|max:60',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'positions_id.required' => 'El ID de la posición es requerido.',
            'positions_id.exists' => 'La posición seleccionada no existe.',

            'name.required' => 'El nombre de la posición es requerido.',
            'name.string' => 'El nombre de la posición debe ser una cadena de texto.',
            'name.max' => 'El nombre de la posición no puede exceder 60 caracteres.',
        ];
    }
}
