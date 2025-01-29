<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTimeLineResource extends JsonResource
{
    /**
     * Transform the vehicle resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'odoo_id' => $this->odoo_id,
            'vehicle_id' => $this->vehicle_id,
            'vehicle_brand_id' => $this->vehicle->vehicle_brand_id,
            'vehicle_brand_name' => $this->vehicle->vehicle_brands->name,
            'vehicle_model_id' => $this->vehicle->vehicle_model_id,
            'vehice_model_name' => $this->vehicle->vehicle_models->name,
            'vehicle_color' => $this->vehicle->color,
            'service_type' => $this->service_type,
            'next_service_date' => $this->next_service_date,
            'next_service_odometer' => $this->next_service_odometer,
            'odometer_unit' => $this->vehicle->odometer_unit,
        ];
    }
}