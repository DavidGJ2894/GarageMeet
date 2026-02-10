<?php

namespace App\Contracts\Services;

use App\DTOs\ClientDTO;

interface ClientServiceInterface
{
    public function createClient(ClientDTO $clientData): array;
    public function updateClient(ClientDTO $clientData): array;
    public function deleteClient(int $peopleId): bool;
    public function getAllClients(int $workshopId): array;
    public function getClientById(int $clientId, int $mechanicalId): ?array;
}
