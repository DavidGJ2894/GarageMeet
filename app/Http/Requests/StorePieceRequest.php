<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePieceRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la pieza es requerido.',
            'name.max' => 'El nombre de la pieza no puede exceder 255 caracteres.',
            'mechanical_workshops_id.required' => 'El ID del taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',
            'price.required' => 'El precio de la pieza es requerido.',
            'price.numeric' => 'El precio de la pieza debe ser un numero.',
            'price.min' => 'El precio de la pieza debe ser mayor o igual a 0.',
        ];
    }
}
