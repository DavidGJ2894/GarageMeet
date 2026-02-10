<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
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
            'services_id' => 'required|int|exists:services,services_id',
            'name' => 'required|string|max:255',
            'mechanical_workshops_id' => 'required|exists:mechanical_workshops,id',
        ];
    }

    public function messages(): array
    {
        return [
            'services_id.required' => 'El ID del servicio es requerido.',
            'services_id.int' => 'El ID del servicio debe ser un número entero.',
            'services_id.exists' => 'El servicio especificado no existe.',

            'name.required' => 'El nombre del servicio es requerido.',
            'name.string' => 'El nombre del servicio debe ser una cadena de texto.',
            'name.max' => 'El nombre del servicio no puede exceder 255 caracteres.',

            'mechanical_workshops_id.required' => 'El ID del taller es requerido.',
            'mechanical_workshops_id.exists' => 'El taller especificado no existe.',
        ];
    }
}
