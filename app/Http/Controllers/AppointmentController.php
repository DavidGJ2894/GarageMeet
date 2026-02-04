<?php

namespace App\Http\Controllers;

use App\Console\Commands\SendAppointmentReminders;
use App\Contracts\Services\AppointmentServiceInterface;
use App\Http\Requests\CancelAppointmentRequest;
use App\Http\Requests\CompleteAppointmentRequest;
use App\Http\Requests\ConfirmAppointmentRequest;
use App\Http\Requests\GetAppointmentsByWorkshopRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    private AppointmentServiceInterface $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Crear nueva solicitud de cita (desde la app móvil)
     */
    public function createRequest(StoreAppointmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->createAppointmentRequest($request->validated());

            return ApiResponse::created(
                'Solicitud de cita creada exitosamente. Te notificaremos cuando sea confirmada.',
                $appointment
            );
        } catch (\Exception $e) {
            return ApiResponse::error('Error al crear la solicitud de cita', $e->getMessage());
        }
    }

    /**
     * Confirmar cita (desde el dashboard del taller)
     */
    public function confirm(ConfirmAppointmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->confirmAppointment($request->validated());

            return ApiResponse::success('Cita confirmada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al confirmar la cita', $e->getMessage());
        }
    }

    /**
     * Obtener todas las citas de un taller específico
     */
    public function getAllByWorkshop(GetAppointmentsByWorkshopRequest $request)
    {
        try {
            $workshopId = $request->validated()['mechanical_workshops_id'];
            $appointments = $this->appointmentService->getAllAppointments($workshopId);

            return response()->json($appointments);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al obtener las citas', $e->getMessage());
        }
    }

    /**
     * Cancelar cita (desde dashboard)
     */
    public function cancel(CancelAppointmentRequest $request)
    {
        try {
            $data = $request->validated();
            $appointment = $this->appointmentService->cancelAppointment($data['appointment_id']);
            return ApiResponse::success('Cita cancelada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al cancelar la cita', $e->getMessage());
        }
    }

    /**
     * Cancelar cita por token público (desde email)
     */
    public function cancelByToken(string $token)
    {
        try {
            $appointment = $this->appointmentService->cancelAppointmentByToken($token);

            // Retornar vista de confirmación de cancelación
            return view('appointments.cancelled', compact('appointment'));
        } catch (\Exception $e) {
            return view('appointments.error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Enviar recordatorio de cita
     */
    public function sendReminder(SendAppointmentReminders $request)
    {
        try {
            $data = $request->validated()['appointment_id'];

            $this->appointmentService->sendReminder($data);

            return ApiResponse::success('Recordatorio enviado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Error al enviar recordatorio', $e->getMessage());
        }
    }

    /**
     * Marcar cita como completada
     */
    public function markAsCompleted(CompleteAppointmentRequest $request)
    {
        try {
            $data = $request->validated()['appointment_id'];

            $appointment = $this->appointmentService->getAppointmentById($data);
            $response = $this->appointmentService->completeAppointment($appointment['appointment_id']);

            return ApiResponse::success('Cita marcada como completada', $response);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al completar la cita', $e->getMessage());
        }
    }
}
