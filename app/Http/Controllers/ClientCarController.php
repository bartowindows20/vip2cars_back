<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\IndexCarRequest;
use App\Http\Requests\Car\StoreCarRequest;
use App\Http\Requests\Car\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\Client;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;

class ClientCarController extends Controller
{
    public function __construct(
        private CarService $carService
    ) {
        //
    }

    public function index(IndexCarRequest $request, Client $client): JsonResponse
    {
        $perPage = $request->validated()['per_page'];

        $paginator = Car::with('carModel.brand')
            ->where('client_id', $client->id)
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $resource = CarResource::collection($paginator)->response()->getData(true);

        return response()->json([
            "success" => true,
            "message" => "Listado de vehículos obtenido con éxito.",
            "data"    => $resource['data'],
            "links"   => $resource['links'],
            "meta"    => $resource['meta'],
        ], 200);
    }

    public function show(Car $car): JsonResponse
    {
        $car->load(['client', 'carModel.brand']);

        return response()->json([
            "success" => true,
            "message" => "Vehículo encontrado con éxito.",
            "data" => new CarResource($car)
        ], 200);
    }

    public function store(StoreCarRequest $request, Client $client)
    {
        $data = $request->validated();

        $existsInTrash = Car::withTrashed()
            ->where('plate', $data['plate'])
            ->onlyTrashed()
            ->exists();

        $car = $this->carService->create($data, $client);

        $message = $existsInTrash
            ? "El vehículo ya existía y fue reactivado con su data actualizada."
            : "Vehículo creado con éxito.";

        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => new CarResource($car)
        ], 201);
    }

    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        $data = $request->validated();

        $carUpdated = $this->carService->update($data, $car);

        return response()->json([
            "success" => true,
            "message" => "Vehículo actualizado con éxito.",
            "data" => new CarResource($carUpdated)
        ], 200);
    }

    public function destroy(Car $car): JsonResponse
    {
        $car->delete();

        return response()->json([
            "success" => true,
            "message" => "Vehículo eliminado con éxito.",
        ], 200);
    }
}
