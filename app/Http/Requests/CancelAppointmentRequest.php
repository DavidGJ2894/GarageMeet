<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CancelAppointmentRequest extends FormRequest
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
            'appointment_id' => 'required|int|exists:appointments,appointment_id',
        ];
    }

        /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'appointment_id.required' => 'La clave primaria de la cita es requerida.',
            'appointment_id.int' => 'La clave primaria de la cita debe ser un entero.',
            'appointment_id.exists' => 'La cita especificada no existe.',
        ];
    }
}
