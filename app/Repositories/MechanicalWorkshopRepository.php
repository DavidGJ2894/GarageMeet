<?php

namespace App\Repositories;

use App\Contracts\Repositories\MechanicalWorkshopRepositoryInterface;
use App\Models\Mechanicals;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MechanicalWorkshopRepository implements MechanicalWorkshopRepositoryInterface
{
    public function create(array $data): array
    {
        $workshop = Mechanicals::create($data);
        return $workshop->toArray();
    }

    public function update(int $id, array $data): array
    {
        $workshop = Mechanicals::findOrFail($id);
        $workshop->update($data);
        return $workshop->toArray();
    }

    public function findById(int $id): ?array
    {
        $workshop = Mechanicals::find($id);
        return $workshop;
    }

}
