<?php

namespace App\Contracts\Services;

use App\DTOs\MechanicalWorkshopDTO;
use App\Http\Requests\StoreMechanicalsRequest;
use App\Http\Requests\UpdateMechanicalsRequest;

interface MechanicalWorkshopServiceInterface
{
    public function createWorkshop( MechanicalWorkshopDTO $data): array;
    public function updateWorkshop(MechanicalWorkshopDTO $data): array;
    public function findWorkshop(int $id): array;


}
