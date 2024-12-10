<?php

namespace App\Http\Resources\ServiceOil;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceOilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'service_id'    => $this->service_id,
            'odoo_id'       => $this->odoo_id,
            'tire_brand_id' => $this->tire_brand_id,
            'oil_viscosity' => $this->oil_viscosity,
            'type_oil'      => $this->type_oil,
            'life_span'     => $this->life_span
        ];
    }
}
