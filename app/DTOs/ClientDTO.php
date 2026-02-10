<?php

namespace App\DTOs;

class ClientDTO
{
    public function __construct(
        public readonly ?int $clientsId,
        public readonly int $mechanicalWorkshopsId,
        public readonly ?int $peoplesId = null,
        public readonly ?PersonDTO $person = null,
        public readonly ?VehicleDTO $vehicle = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'clients_id' => $this->clientsId,
            'mechanical_workshops_id' => $this->mechanicalWorkshopsId,
            'peoples_id' => $this->peoplesId,
            'person' => $this->person?->toArray(),
            'vehicle' => $this->vehicle?->toArray(),
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            clientsId: $data['clients_id'] ?? null,
            mechanicalWorkshopsId: $data['mechanical_workshops_id'],
            peoplesId: $data['peoples_id'] ?? null,
            person: isset($data['person']) ? PersonDTO::fromArray($data['person']) : null,
            vehicle: isset($data['vehicle']) ? VehicleDTO::fromArray($data['vehicle']) : null,
        );
    }

    /**
     * Create a ClientDTO from a StorePeoplesRequest validated data.
     */
    public static function fromStoreRequest(array $validated, array $extra): self
    {
        return new self(
            clientsId: null,
            mechanicalWorkshopsId: $extra['mechanicals_id'],
            person: PersonDTO::fromArray($validated),
            vehicle: VehicleDTO::fromArray($extra['vehicle'][0]),
        );
    }

    /**
     * Create a ClientDTO from an UpdatePeoplesRequest validated data.
     */
    public static function fromUpdateRequest(array $validated, array $extra): self
    {
        return new self(
            clientsId: $extra['clients_id'] ?? null,
            mechanicalWorkshopsId: $extra['mechanical_workshops_id'] ?? 0,
            peoplesId: $validated['peoples_id'] ?? null,
            person: PersonDTO::fromArray($validated),
            vehicle: isset($extra['vehicle'][0])
                ? VehicleDTO::fromArray($extra['vehicle'][0])
                : null,
        );
    }
}
