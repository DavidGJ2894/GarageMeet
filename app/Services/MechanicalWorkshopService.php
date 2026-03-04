<?php

namespace App\Services;

use App\Contracts\Repositories\MechanicalWorkshopRepositoryInterface;
use App\Contracts\Services\MechanicalWorkshopServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\DTOs\MechanicalWorkshopDTO;

class MechanicalWorkshopService implements MechanicalWorkshopServiceInterface
{
    private MechanicalWorkshopRepositoryInterface $workshopRepository;
    private UserServiceInterface $userService;
    public function __construct(MechanicalWorkshopRepositoryInterface $workshopRepository, UserServiceInterface $userService)
    {
        $this->workshopRepository = $workshopRepository;
        $this->userService = $userService;
    }

    public function createWorkshop(MechanicalWorkshopDTO $data): array
    {
        $workshop = $this->workshopRepository->create($data->toArray());
        return $workshop;
    }

    public function updateWorkshop(MechanicalWorkshopDTO $data): array
    {
        $workshop = $this->workshopRepository->update($data->id, $data->toArray());
        return $workshop;
    }

    public function findWorkshop(int $id): array
    {
        $user = $this->userService->getAuthenticatedUser();
        $workshop = $this->workshopRepository->findById($id);
        if (!$workshop) {
            throw new \Exception("Mechanical workshop with ID $id not found.", 404);
        }
        if ($workshop['users_id'] !== $user['users_id']) {
            throw new \Exception("Forbidden', 'Unauthorized access", 403);
        }
        return $workshop;
    }
}
