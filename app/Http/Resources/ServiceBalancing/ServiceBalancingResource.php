<?php

namespace App\Http\Resources\ServiceBalancing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBalancingResource extends JsonResource
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
            'sequence_id'      => $this->sequence_id,
            'odoo_id'          => $this->odoo_id,
            'service_id'       => $this->service_id,
            'location'         => $this->location,
            'lead_used'        => (float) $this->lead_used,
            'type_lead'        => $this->type_lead == '0' ? null : $this->type_lead,
            'balanced'         => $this->balanced,
            'wheel_good_state' => $this->wheel_good_state,
            'wheel_scratched'  => $this->wheel_scratched,
            'wheel_cracked'    => $this->wheel_cracked,
            'wheel_bent'       => $this->wheel_bent,
        ];
    }
}
