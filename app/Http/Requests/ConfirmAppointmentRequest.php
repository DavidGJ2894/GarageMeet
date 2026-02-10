<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmAppointmentRequest extends FormRequest
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
            'confirmed_date' => 'required|date',
            'confirmed_time' => 'required|date_format:H:i',
            'notes' => 'sometimes|string|max:1000',
        ];
    }
        /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'appointment_id.required' => 'El ID de la cita es requerido.',
            'appointment_id.int' => 'El ID de la cita debe ser un número entero.',
            'appointment_id.exists' => 'La cita especificada no existe.',

            'confirmed_date.required' => 'La fecha confirmada es requerida.',
            'confirmed_date.date' => 'La fecha debe tener un formato válido.',

            'confirmed_time.required' => 'La hora de confirmación es requerida.',
            'confirmed_time.date_format' => 'La hora de confirmación debe tener el formato HH:mm.',

            'notes.string' => 'Las notas deben ser una cadena de texto.',
            'notes.max' => 'Las notas no pueden exceder 1000 caracteres.',
        ];
    }
}


