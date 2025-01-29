<?php

namespace App\Http\Resources\Odometer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OdometerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'odoo_id'     => $this->odoo_id,
            'vehicle_id'  => $this->vehicle_id,
            'driver_id'   => $this->driver_id,
            'date'        => $this->date,
            'value'       => $this->value,
            'sequence_id' => $this->sequence_id,
        ];
    }
}
