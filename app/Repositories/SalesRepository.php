<?php

namespace App\Repositories;

use App\Contracts\Repositories\SalesRepositoryInterface;
use App\Models\Services_sales;
use App\Models\Services_by_sales;
use App\Models\pieces_sales;

use function PHPUnit\Framework\isEmpty;

class SalesRepository implements SalesRepositoryInterface
{
    public function create(array $data): array
    {
        $service = [
            'payment_types_id' => $data['payment_types_id'],
            'employees_id' => $data['employees_id'],
            'vehicles_id' => $data['vehicles_id'],
            'mechanical_workshops_id' => $data['mechanical_workshops_id'],
            'date' => $data['date'],
            'price' => $data['price'],
        ];

        $sale = Services_sales::create($service);

        // $this->createServiceSales($data['services'], $sale->services_sales_id);
        //$this->createPieceSales($data['pieces'], $sale->services_sales_id);

        return [
            'services_sales_id' => $sale->services_sales_id,
            'payment_types_id' => $sale->payment_types_id,
            'employees_id' => $sale->employees_id,
            'vehicles_id' => $sale->vehicles_id,
            'mechanical_workshops_id' => $sale->mechanical_workshops_id,
            'date' => $sale->date,
            'price' => $sale->price,
            'services' => $data['services'],
            'pieces' => $data['pieces'],
        ];
    }

    public function update(int $id, array $data): array
    {
        $service = [
            'payment_types_id' => $data['payment_types_id'],
            'employees_id' => $data['employees_id'],
            'vehicles_id' => $data['vehicles_id'],
            'mechanical_workshops_id' => $data['mechanical_workshops_id'],
            'date' => $data['date'],
            'price' => $data['price'],
        ];
        $sale = Services_sales::findOrFail($id);
        $sale->update($service);

        if (!empty($data['services'])) {
            Services_by_sales::where('services_sales_id', $id)->delete();
            foreach ($data['services'] as $serviceId) {
                Services_by_sales::create([
                    'services_sales_id' => $sale->services_sales_id,
                    'services_id' => $serviceId,
                ]);
            }
        }

        if (!empty($data['pieces'])) {
            pieces_sales::where('services_sales_id', $id)->delete();
            foreach ($data['pieces'] as $pieceId) {
                pieces_sales::create([
                    'services_sales_id' => $sale->services_sales_id,
                    'pieces_id' => $pieceId,
                ]);
            }
        }

        return [
            'services_sales_id' => $sale->services_sales_id,
            'payment_types_id' => $sale->payment_types_id,
            'employees_id' => $sale->employees_id,
            'vehicles_id' => $sale->vehicles_id,
            'mechanical_workshops_id' => $sale->mechanical_workshops_id,
            'date' => $sale->date,
            'price' => $sale->price,
            'services' => $data['services'] ? $data['services'] : [],
            'pieces' => $data['pieces'] ? $data['pieces'] : [],
        ];
    }

    public function findById(int $id, int $mechanical_id): ?array
    {
        $sale = Services_sales::where('services_sales_id', $id)
            ->where('mechanical_workshops_id', $mechanical_id)
            ->with([
                'pieces.piece',  // Cargar las piezas con sus detalles
                'services.service',  // Cargar los servicios con sus detalles
                'employee.person',   // Cargar el empleado con los datos de la persona
                'vehicles.makeModel.make',  // Cargar el vehículo con make
                'vehicles.makeModel.model'  // Cargar el vehículo con model
            ])
            ->get();
        $saleData = $sale->map(function ($sale) {
            // Formatear las piezas con ID y nombre
            $pieces = $sale->pieces->map(function ($pieceSale) {
                return [
                    'pieces_id' => $pieceSale->piece->pieces_id,
                    'name' => $pieceSale->piece->name,
                    'price' => $pieceSale->piece->price
                ];
            });

            // Formatear los datos del empleado
            $employee = null;
            if ($sale->employee && $sale->employee->person) {
                $employee = [
                    'employees_id' => $sale->employee->employees_id,
                    'name' => $sale->employee->person->name,
                    'last_name' => $sale->employee->person->last_name,
                    'email' => $sale->employee->person->email,
                    'cellphone_number' => $sale->employee->person->cellphone_number
                ];
            }

            // Formatear los datos del vehículo
            $vehicle = null;
            if ($sale->vehicles && $sale->vehicles->makeModel) {
                $vehicle = [
                    'vehicles_id' => $sale->vehicles->vehicles_id,
                    'plates' => $sale->vehicles->plates,
                    'make' => $sale->vehicles->makeModel->make ? $sale->vehicles->makeModel->make->name : null,
                    'model' => $sale->vehicles->makeModel->model ? $sale->vehicles->makeModel->model->name : null
                ];
            }

            // Formatear los servicios con ID y nombre
            $services = $sale->services->map(function ($serviceSale) {
                return [
                    'services_id' => $serviceSale->service->services_id,
                    'name' => $serviceSale->service->name
                ];
            });

            return [
                'services_sales_id' => $sale->services_sales_id,
                'payment_types_id' => $sale->payment_types_id,
                'mechanical_workshops_id' => $sale->mechanical_workshops_id,
                'date' => $sale->date,
                'price' => $sale->price,
                'employee' => $employee,
                'vehicle' => $vehicle,
                'pieces' => $pieces->toArray(),
                'services' => $services->toArray()
            ];
        });

        return $saleData->first() ?: null;
    }

    public function delete(int $id): bool
    {
        $sale = Services_sales::findOrFail($id);
        return $sale->delete();
    }

    public function getAllByWorkshop(int $workshopId): array
    {
        $sales = Services_sales::where('mechanical_workshops_id', $workshopId)
            ->with([
                'pieces.piece',  // Cargar las piezas con sus detalles
                'services.service',  // Cargar los servicios con sus detalles
                'employee.person',   // Cargar el empleado con los datos de la persona
                'vehicles.makeModel.make',  // Cargar el vehículo con make
                'vehicles.makeModel.model'  // Cargar el vehículo con model
            ])
            ->get();

        return $sales->map(function ($sale) {
            // Formatear las piezas con ID y nombre
            $pieces = $sale->pieces->map(function ($pieceSale) {
                return [
                    'pieces_id' => $pieceSale->piece->pieces_id,
                    'name' => $pieceSale->piece->name,
                    'price' => $pieceSale->piece->price
                ];
            });

            // Formatear los datos del empleado
            $employee = null;
            if ($sale->employee && $sale->employee->person) {
                $employee = [
                    'employees_id' => $sale->employee->employees_id,
                    'name' => $sale->employee->person->name,
                    'last_name' => $sale->employee->person->last_name,
                    'email' => $sale->employee->person->email,
                    'cellphone_number' => $sale->employee->person->cellphone_number
                ];
            }

            // Formatear los datos del vehículo
            $vehicle = null;
            if ($sale->vehicles && $sale->vehicles->makeModel) {
                $vehicle = [
                    'vehicles_id' => $sale->vehicles->vehicles_id,
                    'plates' => $sale->vehicles->plates,
                    'make' => $sale->vehicles->makeModel->make ? $sale->vehicles->makeModel->make->name : null,
                    'model' => $sale->vehicles->makeModel->model ? $sale->vehicles->makeModel->model->name : null
                ];
            }

            // Formatear los servicios con ID y nombre
            $services = $sale->services->map(function ($serviceSale) {
                return [
                    'services_id' => $serviceSale->service->services_id,
                    'name' => $serviceSale->service->name
                ];
            });

            return [
                'services_sales_id' => $sale->services_sales_id,
                'payment_types_id' => $sale->payment_types_id,
                'mechanical_workshops_id' => $sale->mechanical_workshops_id,
                'date' => $sale->date,
                'price' => $sale->price,
                'employee' => $employee,
                'vehicle' => $vehicle,
                'pieces' => $pieces->toArray(),
                'services' => $services->toArray()
            ];
        })->toArray();
    }
}
