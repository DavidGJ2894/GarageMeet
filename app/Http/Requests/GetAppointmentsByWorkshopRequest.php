<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAppointmentsByWorkshopRequest extends FormRequest
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
            'mechanical_workshops_id' => 'required|int|exists:mechanical_workshops,id',
        ];
    }

            /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'mechanical_workshops_id.required' => 'El taller es requerido.',
            'mechanical_workshops_id.int' => 'El taller debe ser un entero.',
            'mechanical_workshops_id.exists' => 'El taller seleccionado no existe.',
        ];
    }
}
