<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePieceRequest extends FormRequest
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
            'pieces_id' => 'required|exists:pieces,pieces_id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pieces_id.required' => 'El ID de la pieza es requerido.',
            'pieces_id.exists' => 'La pieza especificada no existe.',
            'name.required' => 'El nombre de la pieza es requerido.',
            'name.max' => 'El nombre de la pieza no puede exceder 255 caracteres.',
            'price.required' => 'El precio de la pieza es requerido.',
            'price.numeric' => 'El precio de la pieza debe ser un numero.',
            'price.min' => 'El precio de la pieza debe ser mayor o igual a 0.',
        ];
    }
}
