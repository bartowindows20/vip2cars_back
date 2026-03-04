<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'plate'             => $this->plate,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'car_model'         => new CarModelResource($this->whenLoaded('carModel')),
            'client'            => new ClientResource($this->whenLoaded('client')),
        ];
    }
}
