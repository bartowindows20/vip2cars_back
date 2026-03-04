<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class CarService
{
    public function create(array $data, Client $client): Car
    {
        $car = Car::withTrashed()
            ->where('plate', $data['plate'])
            ->first();

        if ($car) {
            $car->restore();
            $car->client_id = $client->id;
            $car->update($data);
        } else {
            $car = $client->cars()->create($data);
        }

        return $car->load(['client', 'carModel.brand']);
    }

    public function update(array $data, Car $car): Car
    {
        return DB::transaction(function () use ($data, $car) {

            $car = Car::where('id', $car->id)->lockForUpdate()->first();

            $car->update($data);
            $car->refresh();

            return $car->load(['client', 'carModel.brand']);
        });
    }
}
