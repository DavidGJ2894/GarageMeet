<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\DTOs\ClientDTO;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function __construct(
        private readonly ClientServiceInterface $clientService,
    ) {}

    public function create(StoreClientRequest $request): JsonResponse
    {
        try {
            $clientData = ClientDTO::fromStoreRequest(
                $request->validated(),
                $request->only(['mechanicals_id', 'vehicle'])
            );

            $result = $this->clientService->createClient($clientData);

            return ApiResponse::created('Client created successfully', $result);
        } catch (\Exception $e) {
            return ApiResponse::error('Error creating client', $e->getMessage());
        }
    }

    public function update(UpdateClientRequest $request): JsonResponse
    {
        try {
            $clientData = ClientDTO::fromUpdateRequest(
                $request->validated(),
                $request->only(['clients_id', 'mechanical_workshops_id', 'vehicle'])
            );

            $result = $this->clientService->updateClient($clientData);

            return ApiResponse::success('Client updated successfully', $result);
        } catch (\Exception $e) {
            return ApiResponse::error('Error updating client', $e->getMessage());
        }
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $this->clientService->deleteClient(
                (int) $request->input('peoples_id')
            );

            return ApiResponse::success('Client deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Error deleting client', $e->getMessage());
        }
    }

    public function getAll(Request $request): JsonResponse
    {
        try {
            $clients = $this->clientService->getAllClients(
                (int) $request->input('mechanical_workshops_id')
            );

            return ApiResponse::success('Clients retrieved successfully', $clients);
        } catch (\Exception $e) {
            return ApiResponse::error('Error retrieving clients', $e->getMessage());
        }
    }

    public function getById(Request $request): JsonResponse
    {
        try {
            $client = $this->clientService->getClientById(
                (int) $request->input('client_id'),
                (int) $request->input('mechanical_workshops_id')
            );

            if (!$client) {
                return ApiResponse::notFound('Client not found');
            }

            return ApiResponse::success('Client retrieved successfully', $client);
        } catch (\Exception $e) {
            return ApiResponse::error('Error retrieving client', $e->getMessage());
        }
    }
}
