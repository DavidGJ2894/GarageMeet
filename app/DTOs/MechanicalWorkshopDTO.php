<?php

namespace App\DTOs;

class MechanicalWorkshopDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $users_id,
        public readonly ?string $external_cities_id,
        public readonly ?string $external_states_id,
        public readonly ?string $name,
        public readonly ?string $cellphone_number,
        public readonly ?string $email,
        public readonly ?string $address,
        public readonly ?string $google_maps_link
    ) {}
/**
 * Undocumented function
 *
 * @param array $validated
 * @return self
 */
    public static function fromStoreRequest(array $validated): self
    {
        return new self(
            id: $validated['id'] ?? null,
            users_id: $validated['users_id'],
            external_cities_id: $validated['external_cities_id'],
            external_states_id: $validated['external_states_id'],
            name: $validated['name'],
            cellphone_number: $validated['cellphone_number'],
            email: $validated['email'],
            address: $validated['address'],
            google_maps_link: $validated['google_maps_link'] ?? null
        );
    }

    public static function fromUpdateRequest(array $validated): self
    {
        return new self(
            id: $validated['id'],
            users_id: $validated['users_id'] ?? null,
            external_cities_id: $validated['external_cities_id'] ?? null,
            external_states_id: $validated['external_states_id'] ?? null,
            name: $validated['name'] ?? null,
            cellphone_number: $validated['cellphone_number'] ?? null,
            email: $validated['email'] ?? null,
            address: $validated['address'] ?? null,
            google_maps_link: $validated['google_maps_link'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'users_id' => $this->users_id,
            'external_cities_id' => $this->external_cities_id,
            'external_states_id' => $this->external_states_id,
            'name' => $this->name,
            'cellphone_number' => $this->cellphone_number,
            'email' => $this->email,
            'address' => $this->address,
            'google_maps_link' => $this->google_maps_link,
        ], fn ($value) => $value !== null);
    }
}
