<?php

namespace App\Http\Resources\Vehicle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'plate' => $this->plate,
            'nickname' => $this->nickname,
            'vehicle_brand_id' => $this->vehicle_brand_id,
            'vehicle_brand_name' => $this->vehicle_brands->name ?? $this->brand_name,
            'vehicle_model_id' => $this->vehicle_model_id,
            'vehicle_model_name' => $this->vehicle_models->name ?? $this->model_name,
            'register_date' => $this->register_date,
            'color' => $this->color,
            'color_hex' => $this->color_hex,
            'year' => $this->year,
            'transmission' => $this->transmission,
            'fuel' => $this->fuel,
            'odometer' => $this->odometer,
            'icon'     => $this->icon,
            'sequence_id' => $this->sequence_id,
            'vehicle_image' => $this->vehicle_image,
            'timeline_order' => $this->timeline_order
        ];
    }
}