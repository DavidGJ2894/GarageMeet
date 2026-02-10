<?php

namespace App\Contracts\Repositories;

interface ClientRepositoryInterface
{
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function findById(int $clientId, int $mechanicalId): ?array;
    public function delete(int $id): bool;
    public function getAllByWorkshop(int $workshopId): array;
}
