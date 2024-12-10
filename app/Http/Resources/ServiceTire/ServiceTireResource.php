<?php

namespace App\Http\Resources\ServiceTire;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'odoo_id'            => $this->odoo_id,
            'service_id'         => $this->service_id,
            'location'           => $this->location,
            'depth'              => $this->depth,
            'starting_pressure'  => $this->starting_pressure,
            'finishing_pressure' => $this->finishing_pressure,
            'dot'                => (int) $this->dot,
            'tire_brand_id'      => $this->tire_brand_id,
            'tire_model_id'      => $this->tire_model_id,
            'tire_size_id'       => $this->tire_size_id,
            'regular'            => $this->regular,
            'staggered'          => $this->staggered,
            'central'            => $this->central,
            'right_shoulder'     => $this->right_shoulder,
            'left_shoulder'      => $this->left_shoulder,
            'not_apply'          => $this->not_apply,
            'bulge'              => $this->bulge,
            'perforations'       => $this->perforations,
            'vulcanized'         => $this->vulcanized,
            'aging'              => $this->aging,
            'cracked'            => $this->cracked,
            'deformations'       => $this->deformations,
            'separations'        => $this->separations,
            'sequence_id'        => $this->sequence_id,
        ];
    }
}
