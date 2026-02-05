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
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Appointment lifecycle controller.
 *
 * Manages appointment operations: creation, confirmation, cancellation, and completion.
 * Appointments follow the flow: pending → confirmed → completed/cancelled.
 *
 * @package App\Http\Controllers
 * @author David Garcia Jeronimo <davidgarcia2809@gmail.com>
 */
class AppointmentController extends Controller
{
    private AppointmentServiceInterface $appointmentService;

    public function __construct(AppointmentServiceInterface $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Creates a new pending appointment request.
     *
     * @param StoreAppointmentRequest $request Validated appointment data
     * @return JsonResponse Created appointment (201)
     */
    public function createAppointment(StoreAppointmentRequest $request): JsonResponse
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
     * Confirms a pending appointment and notifies the client.
     *
     * @param ConfirmAppointmentRequest $request Confirmation data
     * @return JsonResponse Confirmed appointment
     */
    public function confirmAppointment(ConfirmAppointmentRequest $request): JsonResponse
    {
        try {
            $appointment = $this->appointmentService->confirmAppointment($request->validated());

            return ApiResponse::success('Cita confirmada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al confirmar la cita', $e->getMessage());
        }
    }

    /**
     * Retrieves all appointments for a specific workshop.
     *
     * @param GetAppointmentsByWorkshopRequest $request Workshop ID
     * @return JsonResponse List of workshop appointments
     */
    public function getAllAppointmentsByWorkshop(GetAppointmentsByWorkshopRequest $request): JsonResponse
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
     * Cancels an appointment via authenticated API request.
     *
     * @param CancelAppointmentRequest $request Appointment ID
     * @return JsonResponse Cancellation confirmation
     */
    public function cancelAppointment(CancelAppointmentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated()['appointment_id'];
            $appointment = $this->appointmentService->cancelAppointment($data);
            return ApiResponse::success('Cita cancelada exitosamente', $appointment);
        } catch (\Exception $e) {
            return ApiResponse::error('Error al cancelar la cita', $e->getMessage());
        }
    }

    /**
     * Cancels an appointment via email token without authentication.
     *
     * @param string $token Unique cancellation token
     * @return View Confirmation or error view
     */
    public function cancelAppointmentByToken(string $token): View
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
     * Sends an appointment reminder via email.
     *
     * @param SendAppointmentReminders $request Appointment ID
     * @return JsonResponse Send confirmation
     */
    public function sendAppointmentNotificationByEmail(SendAppointmentReminders $request): JsonResponse
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
     * Marks an appointment as completed after service completion.
     *
     * @param CompleteAppointmentRequest $request Appointment ID
     * @return JsonResponse Completed appointment
     */
    public function markAppointmentAsCompleted(CompleteAppointmentRequest $request): JsonResponse
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
