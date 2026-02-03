<?php

namespace App\Http\Controllers;

use App\Contracts\Services\AppointmentServiceInterface;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Responses\ApiResponse;
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
            $data = $request->validated();
            $appointment = $this->appointmentService->createAppointmentRequest($data);

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
    public function confirm(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id',
                'confirmed_date' => 'required|date|after:now',
                'confirmed_time' => 'required|date_format:H:i:s',
                'notes' => 'sometimes|string|max:1000'
            ]);

            $appointment = $this->appointmentService->confirmAppointment($data);

            return ApiResponse::success('Cita confirmada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al confirmar la cita', $e->getMessage());
        }
    }

    /**
     * Obtener todas las citas de un taller específico
     */
    public function getAllByWorkshop(Request $request)
    {
        try {
            $workshopId = $request->mechanical_workshops_id;
            $appointments = $this->appointmentService->getAllAppointments($workshopId);

            return response()->json($appointments);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al obtener las citas', $e->getMessage());
        }
    }

    /**
     * Obtener todas las citas de un taller
     */

    /**
     * Obtener cita por ID
     */
    public function getById(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id'
            ]);

            $appointment = $this->appointmentService->getAppointmentById($data['appointment_id']);

            if (!$appointment) {
                return ApiResponse::notFound('Cita no encontrada');
            }

            return ApiResponse::success('Cita obtenida exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al obtener la cita', $e->getMessage());
        }
    }

    /**
     * Actualizar cita
     */
    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id',
                'client_name' => 'sometimes|string|max:255',
                'client_email' => 'sometimes|email|max:255',
                'client_phone' => 'sometimes|string|max:20',
                'description' => 'sometimes|string|max:1000',
                'appointment_date' => 'sometimes|date',
                'status' => 'sometimes|in:pending,confirmed,cancelled,completed',
                'notes' => 'sometimes|string|max:1000'
            ]);

            $appointmentId = $data['appointment_id'];
            unset($data['appointment_id']); // Remover el ID de los datos a actualizar

            // Usar el repositorio directamente para actualizar
            $appointmentRepository = app(\App\Contracts\Repositories\AppointmentRepositoryInterface::class);
            $updated = $appointmentRepository->update($appointmentId, $data);

            if (!$updated) {
                return ApiResponse::error('No se pudo actualizar la cita');
            }

            $appointment = $this->appointmentService->getAppointmentById($appointmentId);

            return ApiResponse::success('Cita actualizada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al actualizar la cita', $e->getMessage());
        }
    }

    /**
     * Eliminar cita
     */
    public function delete(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id'
            ]);

            // Usar el repositorio directamente para eliminar
            $appointmentRepository = app(\App\Contracts\Repositories\AppointmentRepositoryInterface::class);
            $deleted = $appointmentRepository->delete($data['appointment_id']);

            if (!$deleted) {
                return ApiResponse::error('No se pudo eliminar la cita');
            }

            return ApiResponse::success('Cita eliminada exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Error al eliminar la cita', $e->getMessage());
        }
    }

    /**
     * Cancelar cita (desde dashboard)
     */
    public function cancel(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id'
            ]);

            $appointment = $this->appointmentService->cancelAppointment($data['appointment_id']);

            return ApiResponse::success('Cita cancelada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al cancelar la cita', $e->getMessage());
        }
    }

    /**
     * Cancelar cita por token público (desde email)
     */
    public function cancelByToken(Request $request, string $token)
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
    public function sendReminder(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id'
            ]);

            $this->appointmentService->sendReminder($data['appointment_id']);

            return ApiResponse::success('Recordatorio enviado exitosamente');
        } catch (\Exception $e) {
            return ApiResponse::error('Error al enviar recordatorio', $e->getMessage());
        }
    }

    /**
     * Marcar cita como completada
     */
    public function markAsCompleted(Request $request)
    {
        try {
            $data = $request->validate([
                'appointment_id' => 'required|exists:appointments,appointment_id',
                'notes' => 'sometimes|string|max:1000'
            ]);

            $appointment = $this->appointmentService->getAppointmentById($data['appointment_id']);

            if (!$appointment) {
                return ApiResponse::notFound('Cita no encontrada');
            }

            // Marcar como completada usando el repositorio directamente
            $appointmentRepository = app(\App\Contracts\Repositories\AppointmentRepositoryInterface::class);
            $appointmentRepository->update($data['appointment_id'], [
                'status' => 'completed',
                'notes' => $data['notes'] ?? $appointment['notes']
            ]);

            $updatedAppointment = $this->appointmentService->getAppointmentById($data['appointment_id']);

            return ApiResponse::success('Cita marcada como completada', $updatedAppointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al completar la cita', $e->getMessage());
        }
    }
}
