<?php

namespace App\Http\Resources\VehicleSummary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'vehicle_id'           => $this->vehicle_id,
            'prom_km_month'        => $this->prom_km_month,
            'visits_number'        => $this->visits_number,
            'last_oil_change_date' => $this->last_oil_change_date,
            'last_oil_change_km'   => $this->last_oil_change_km,
            'accum_km_traveled'    => $this->accum_km_traveled,
            'accum_days_total'     => $this->accum_days_total,
            'accum_oil_changes'    => $this->accum_oil_changes,
            'initial_date'         => $this->initial_date,
            'initial_km'           => $this->initial_km,
            'last_visit'           => $this->last_visit,
            'sequence_id'          => $this->sequence_id,
        ];
    }
}
