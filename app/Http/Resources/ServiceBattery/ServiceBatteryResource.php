<?php

namespace App\Http\Resources\ServiceBattery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBatteryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'sequence_id'               => $this->sequence_id,
            'odoo_id'                   => $this->odoo_id,
            'battery_brand_id'          => $this->battery_brand_id == 0 ? null : $this->battery_brand_id,
            'battery_model_id'          => $this->battery_model_id == 0 ? null : $this->battery_model_id,
            'date_of_purchase'          => $this->date_of_purchase,
            'serial_product'            => $this->serial_product,
            'warranty_date'             => $this->warranty_date,
            'service_id'                => $this->service_id,
            'amperage'                  => $this->amperage,
            'alternator_voltage'        => (float) $this->alternator_voltage,
            'battery_voltage'           => (float) $this->battery_voltage,
            'status_battery'            => $this->status_battery,
            'status_alternator'         => $this->status_alternator,
            'good_condition'            => $this->good_condition,
            'liquid_leakage'            => $this->liquid_leakage,
            'corroded_terminals'        => $this->corroded_terminals,
            'frayed_cables'             => $this->frayed_cables,
            'inflated'                  => $this->inflated,
            'cracked_case'              => $this->cracked_case,
            'new_battery'               => $this->new_battery,
            'replaced_battery'          => $this->replaced_battery,
            'serial_product'            => $this->serial_produc,
            'starting_current'          => $this->starting_current,
            'accumulated_load_capacity' => $this->accumulated_load_capacity,
            'health_status'             => $this->health_status,
            'health_status'             => $this->formatHealthStatus(),
            'health_status_final'       => $this->health_status_final,
            'health_percentage'         => $this->health_percentage,
            'final_health_good_state'   => $this->health_status_final === 'Buen estado',
            'final_heath_charge_required' => $this->health_status_final === 'Requiere carga',
            'final_heath_deficient'     => $this->health_status_final === 'Deficiente',
            'final_heath_damaged'       => $this->health_status_final === 'Dañada',
            'health_initial_gradient'   => $this->calculateInitialGradient(),
            'health_final_gradient'     => $this->calculateFinalGradient(),
            'health_gradient_background' => $this->calculateGradientBackground(),
            'created_at'                => ($this->created_at)->format('Y-m-d H:m:s'),
        ];
    }

    private function calculateInitialGradient(): string
    {
        switch ($this->health_status_final) {
            case 'Buen estado':
                return '#46CC34';
            case 'Próximo a Reemplazo':
                return '#FFC633';
            case 'Deficiente':
                return '#FF3333';
            case 'Dañada':
                return '#252525';
            default:
                return '';
        }
    }

    private function calculateFinalGradient(): string
    {
        switch ($this->health_status_final) {
            case 'Buen estado':
                return '#25D073';
            case 'Próximo a Reemplazo':
            case 'Deficiente':
            case 'Dañada':
                return '#FCA723';
            default:
                return '';
        }
    }

    private function calculateGradientBackground(): string
    {
        switch ($this->health_status_final) {
            case 'Buen estado':
                return '#DDFEDC';
            case 'Próximo a Reemplazo':
                return '#FDEACD';
            case 'Deficiente':
                return '#FDCDCD';
            case 'Dañada':
                return '#D9D9D9';
            default:
                return '';
        }
    }

    private function formatHealthStatus(): string
    {
        $healthStatus = str_replace('%   ', '', $this->health_status);
        if ($healthStatus === 'Deficiente - Reemplazar') {
            return 'Reemplazar';
        }
        return $healthStatus;
    }
}
