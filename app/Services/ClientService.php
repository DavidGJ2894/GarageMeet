<?php

namespace App\Services;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\PeopleRepositoryInterface;
use App\Contracts\Repositories\VehiclesClientsRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\DTOs\ClientDTO;
use Illuminate\Support\Facades\DB;

class ClientService implements ClientServiceInterface
{
    public function __construct(
        private readonly PeopleRepositoryInterface $peopleRepository,
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly VehiclesClientsRepositoryInterface $vehicleRepository,
    ) {}

    public function createClient(ClientDTO $clientData): array
    {
        return DB::transaction(function () use ($clientData) {
            $person = $this->peopleRepository->create(
                $clientData->person->toArray()
            );

            $makeModelId = $this->vehicleRepository->getMakeModelId(
                $clientData->vehicle->makeId,
                $clientData->vehicle->modelId
            );

            $client = $this->clientRepository->create([
                'mechanical_workshops_id' => $clientData->mechanicalWorkshopsId,
                'peoples_id' => $person['peoples_id'],
            ]);

            $vehicle = $this->vehicleRepository->create([
                'clients_id' => $client['clients_id'],
                'plates' => $clientData->vehicle->plates,
                'makes_model_id' => $makeModelId,
            ]);

            return [
                'person' => $person,
                'client' => $client,
                'vehicle' => $vehicle,
            ];
        });
    }

    public function updateClient(ClientDTO $clientData): array
    {
        return DB::transaction(function () use ($clientData) {
            $person = $this->peopleRepository->update(
                $clientData->peoplesId,
                $clientData->person->toArray()
            );

            $makeModelId = $this->vehicleRepository->getMakeModelId(
                $clientData->vehicle->makeId,
                $clientData->vehicle->modelId
            );

            $vehicle = $this->vehicleRepository->update(
                $clientData->vehicle->vehiclesId,
                [
                    'plates' => $clientData->vehicle->plates,
                    'makes_model_id' => $makeModelId,
                ]
            );

            return [
                'person' => $person,
                'client_id' => $clientData->clientsId,
                'vehicle' => $vehicle,
            ];
        });
    }

    public function deleteClient(int $peopleId): bool
    {
        return DB::transaction(function () use ($peopleId) {
            return $this->peopleRepository->delete($peopleId);
        });
    }

    public function getAllClients(int $workshopId): array
    {
        return $this->clientRepository->getAllByWorkshop($workshopId);
    }

    public function getClientById(int $clientId, int $mechanicalId): ?array
    {
        return $this->clientRepository->findById($clientId, $mechanicalId);
    }
}
