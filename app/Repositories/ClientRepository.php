<?php

namespace App\Repositories;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Models\clients;
use App\Models\MakeModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientRepository implements ClientRepositoryInterface
{
    public function create(array $data): array
    {
        $client = clients::create($data);

        return [
            'clients_id' => $client->clients_id,
            'mechanical_workshops_id' => $client->mechanical_workshops_id,
            'peoples_id' => $client->peoples_id,
        ];
    }

    public function update(int $id, array $data): array
    {
        $client = clients::findOrFail($id);
        $client->update($data);

        return [
            'clients_id' => $client->clients_id,
            'mechanical_workshops_id' => $client->mechanical_workshops_id,
            'peoples_id' => $client->peoples_id,
        ];
    }

    public function findById(int $clientId, int $mechanicalId): ?array
    {
        $client = clients::with(['person', 'vehicles.makeModel.make', 'vehicles.makeModel.model'])
            ->where('clients_id', $clientId)
            ->where('mechanical_workshops_id', $mechanicalId)
            ->first();

        if (!$client) {
            return null;
        }

        $clientArray = $client->toArray();

        $clientArray['vehicles'] = $this->enrichVehiclesWithMakeModel(
            $clientArray['vehicles'] ?? []
        );

        return $clientArray;
    }

    public function delete(int $id): bool
    {
        $client = clients::findOrFail($id);

        return $client->delete();
    }

    public function getAllByWorkshop(int $workshopId): array
    {
        $clients = clients::with(['person', 'vehicles.makeModel.make', 'vehicles.makeModel.model'])
            ->where('mechanical_workshops_id', $workshopId)
            ->get();

        return $clients->map(function ($client) {
            $vehicles = $this->enrichVehiclesWithMakeModel(
                $client->vehicles->toArray()
            );

            $firstVehicle = $vehicles[0] ?? null;
            $clientInfo = $client->person->name . ' ' . $client->person->last_name;

            if ($firstVehicle) {
                $clientInfo .= ' - ' . ($firstVehicle['make'] ?? '') . ' '
                    . ($firstVehicle['model'] ?? '') . ' - ' . $firstVehicle['plates'];
            }

            return [
                'clientInfo' => $clientInfo,
                'clients_id' => $client->clients_id,
                'peoples_id' => $client->peoples_id,
                'vehicles_id' => $firstVehicle['vehicles_id'] ?? null,
                'mechanical_workshops_id' => $client->mechanical_workshops_id,
                'person' => $client->person->toArray(),
                'vehicles' => $vehicles,
            ];
        })->toArray();
    }

    /**
     * Enrich vehicle arrays with make and model names from their MakeModel relationship.
     */
    private function enrichVehiclesWithMakeModel(array $vehicles): array
    {
        return array_map(function (array $vehicle) {
            $makeModelInfo = isset($vehicle['makes_model_id'])
                ? $this->getMakeModelName($vehicle['makes_model_id'])
                : ['make' => null, 'model' => null, 'make_id' => null, 'model_id' => null];

            return [
                'vehicles_id' => $vehicle['vehicles_id'],
                'plates' => $vehicle['plates'],
                'makes_model_id' => $vehicle['makes_model_id'],
                'make' => $makeModelInfo['make'],
                'model' => $makeModelInfo['model'],
                'make_id' => $makeModelInfo['make_id'],
                'model_id' => $makeModelInfo['model_id'],
            ];
        }, $vehicles);
    }

    private function getMakeModelName(int $makeModelId): array
    {
        $makeModel = MakeModel::with(['make', 'model'])->find($makeModelId);

        if (!$makeModel) {
            return ['make' => null, 'model' => null, 'make_id' => null, 'model_id' => null];
        }

        return [
            'make' => $makeModel->make->name,
            'model' => $makeModel->model->name,
            'make_id' => $makeModel->make->make_id,
            'model_id' => $makeModel->model->model_id,
        ];
    }
}
