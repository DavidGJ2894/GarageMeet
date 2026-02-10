<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
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
            'name' => 'required|string|max:60',
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la posicion es requerido.',
            'name.max' => 'El nombre de la posicion no puede exceder 60 caracteres.',
            'mechanical_workshops_id.required' => 'El ID del taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',
        ];
    }
}
