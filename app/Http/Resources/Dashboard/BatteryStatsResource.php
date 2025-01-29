<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatteryStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Verificar si $this no es nulo y si la propiedad health_status_final está definida
        if ($this && isset($this->health_status_final)) {
            return [
                'id'                           => $this->id ?? null,
                'battery_brand_name'           => $this->battery_brand_name ?? null,
                'amperage'                     => $this->amperage ?? null,
                'accumulated_load_capacity'    => $this->accumulated_load_capacity ?? null,
                'battery_voltage'              => $this->battery_voltage ?? null,
                'alternator_voltage'           => $this->alternator_voltage ?? null,
                'final_health_good_state'      => $this->health_status_final === 'Buen estado',
                'final_heath_charge_required'  => $this->health_status_final === 'Requiere carga',
                'final_heath_deficient'        => $this->health_status_final === 'Deficiente',
                'final_heath_damaged'          => $this->health_status_final === 'Dañada',
            ];
        } else {
            // Devolver un array vacío si $this es nulo o si health_status_final no está definida
            return [];
        }
    }
}
