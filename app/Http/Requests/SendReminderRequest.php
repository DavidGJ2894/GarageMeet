<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_id' => 'required|int|exists:appointments,appointment_id'
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_id.required' => 'El ID de la cita es obligatorio.',
            'appointment_id.int' => 'El ID de la cita debe ser un número entero.',
            'appointment_id.exists' => 'El ID de la cita no existe'
        ];
    }
}
