<?php

namespace App\Http\Resources\AppWarning;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutoHealingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'service_id'    => $this->service_id,
            'service_date'  => $this->service_date,
            'tire_location' => $this->tire_location,
            'mm_consumed'   => $this->mm_consumed,
            'tread_depth'   => $this->tread_depth,
            'plate'         => $this->plate,
            'email'         => $this->email,
            'owner_id'      => $this->res_partner_id,
            'vehicle_id'    => $this->vehicle_id,
        ];
    }
}
