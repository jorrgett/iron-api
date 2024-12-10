<?php

namespace App\Http\Resources\VehicleModel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'vehicle_brand_id' => $this->vehicle_brand_id,
            'name'             => $this->name,
            'odoo_id'          => $this->odoo_id,
            'sequence_id'      => $this->sequence_id,
        ];
    }
}
