<?php

namespace App\Http\Resources\OilHistory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OilHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'vehicle_id'        => $this->vehicle_id,
            'service_id'        => $this->service_id,
            'service_state'     => $this->service_state,
            'change_date'       => $this->change_date,
            'change_km'         => $this->change_km,
            'change_next_km'    => $this->change_next_km,
            'change_next_date'  => $this->change_next_date,
            'life_span'         => $this->life_span,
            'life_span_standar' => $this->life_span_standar,
            'sequence_id'       => $this->sequence_id
        ];
    }
}
