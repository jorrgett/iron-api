<?php

namespace App\Http\Resources\TireStandar;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TireStandarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'otd' => $this->otd,
            'tire_size' => $this->tire_size,
            'sequence_id' => $this->sequence_id
        ];
    }
}
