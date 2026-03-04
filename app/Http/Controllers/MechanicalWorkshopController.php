<?php

namespace App\Http\Controllers;

use App\Contracts\Services\MechanicalWorkshopServiceInterface;
use App\Http\Requests\StoreMechanicalsRequest;
use App\Http\Requests\UpdateMechanicalsRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Mechanicals;
use Illuminate\Http\Request;
use App\DTOs\MechanicalWorkshopDTO;

class MechanicalWorkshopController extends Controller
{
    private MechanicalWorkshopServiceInterface $mechanicalWorkshopService;

    public function __construct(MechanicalWorkshopServiceInterface $mechanicalWorkshopService)
    {
        $this->mechanicalWorkshopService = $mechanicalWorkshopService;
    }

    public function create(StoreMechanicalsRequest $request)
    {
        try {
            $workshopDTO = MechanicalWorkshopDTO::fromStoreRequest($request->validated());
            $workshop = $this->mechanicalWorkshopService->createWorkshop($workshopDTO);
            return ApiResponse::created('Mechanical workshop created successfully', $workshop);
        } catch (\Exception $e) {
            return ApiResponse::error('Error creating mechanical workshop', $e->getMessage());
        }
    }

    public function update(UpdateMechanicalsRequest $data)
    {
        try {
            $workshopDTO = MechanicalWorkshopDTO::fromUpdateRequest($data->validated());
            $workshop = $this->mechanicalWorkshopService->updateWorkshop($workshopDTO);
            return ApiResponse::success('Mechanical workshop updated successfully', $workshop);
        } catch (\Exception $e) {
            return ApiResponse::error('Error updating mechanical workshop', $e->getMessage());
        }
    }

    public function getUserWorkshop(int $id)
    {
        try {

            $workshop = $this->mechanicalWorkshopService->findWorkshop($id);
            return ApiResponse::success('User workshop retrieved successfully', $workshop);
        } catch (\Exception $e) {
            return ApiResponse::error('Error fetching user workshop', $e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
