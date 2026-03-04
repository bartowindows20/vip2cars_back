<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\IndexClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{

    public function __construct(
        private ClientService $clientService
    ) {
        //
    }

    public function index(IndexClientRequest $request): JsonResponse
    {
        $perPage = $request->validated()['per_page'];

        $paginator = Client::with('cars.carModel.brand')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $resource = ClientResource::collection($paginator)->response()->getData(true);

        return response()->json([
            "success" => true,
            "message" => "Listado de clientes obtenido con éxito.",
            "data"    => $resource['data'],
            "links"   => $resource['links'],
            "meta"    => $resource['meta'],
        ], 200);
    }

    public function show(Client $client): JsonResponse
    {
        $client->load('cars.carModel.brand');

        return response()->json([
            "success" => true,
            "message" => "Cliente encontrado con éxito.",
            "data" => new ClientResource($client)
        ], 200);
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $data = $request->validated();

        $existsInTrash = Client::withTrashed()
            ->where('document_type', $data['document_type'])
            ->where('document_number', $data['document_number'])
            ->onlyTrashed()
            ->exists();

        $client = $this->clientService->create($data);

        $message = $existsInTrash
            ? "El cliente ya existía y fue reactivado con su data actualizada."
            : "Cliente creado con éxito.";

        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => new ClientResource($client)
        ], 201);
    }

    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $data = $request->validated();

        $client = $this->clientService->update($data, $client);

        return response()->json([
            "success" => true,
            "message" => "Cliente actualizado con éxito.",
            "data" => new ClientResource($client)
        ], 200);
    }

    public function destroy(Client $client): JsonResponse
    {
        $this->clientService->delete($client);

        return response()->json([
            "success" => true,
            "message" => "Cliente y sus vehículos asociados eliminados con éxito.",
        ], 200);
    }
}
