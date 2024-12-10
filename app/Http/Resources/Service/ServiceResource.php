<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'odoo_id'          => $this->odoo_id,
            'store_id'         => $this->store_id,
            'driver_id'        => $this->driver_id,
            'driver_name'      => $this->driver_name,
            'owner_id'         => $this->owner_id,
            'owner_name'       => $this->owner_name,
            'vehicle_id'       => $this->vehicle_id,
            'date'             => $this->date,
            'odometer'         => $this->odometer,
            'odometer_id'      => $this->odometer_id,
            'state'            => $this->state,
            'rotatiton_x'      => $this->rotation_x,
            'rotatiton_lineal' => $this->rotation_lineal,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'sequence_id'      => $this->sequence_id
        ];
    }
}
