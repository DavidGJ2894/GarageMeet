<?php

namespace App\DTOs;

class VehicleDTO
{
    public function __construct(
        public readonly ?int $vehiclesId,
        public readonly ?int $clientsId,
        public readonly string $plates,
        public readonly int $makeId,
        public readonly int $modelId,
        public readonly ?int $makesModelId = null,
        public readonly ?string $makeName = null,
        public readonly ?string $modelName = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'vehicles_id' => $this->vehiclesId,
            'clients_id' => $this->clientsId,
            'plates' => $this->plates,
            'make_id' => $this->makeId,
            'model_id' => $this->modelId,
            'makes_model_id' => $this->makesModelId,
            'make' => $this->makeName,
            'model' => $this->modelName,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            vehiclesId: $data['vehicles_id'] ?? null,
            clientsId: $data['clients_id'] ?? null,
            plates: $data['plates'],
            makeId: $data['make_id'],
            modelId: $data['model_id'],
            makesModelId: $data['makes_model_id'] ?? null,
            makeName: $data['make'] ?? null,
            modelName: $data['model'] ?? null,
        );
    }
}
