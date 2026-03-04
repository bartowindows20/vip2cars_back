<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'names'             => $this->names,
            'paternal_surname'  => $this->paternal_surname,
            'maternal_surname'  => $this->maternal_surname,
            'document_type'     => $this->document_type,
            'document_number'   => $this->document_number,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'created_at'        => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'        => $this->updated_at->format('Y-m-d H:i:s'),
            'cars'              => CarResource::collection($this->whenLoaded('cars')),
        ];
    }
}
