<?php

namespace App\Transformers;

use Carbon\Carbon;
use App\Models\ServiceBattery;

class ServiceBatteryTransformer extends Transformer
{
    private const HEALTH_THRESHOLD = 75;
    private const VOLTAGE_GOOD = 12.4;
    private const VOLTAGE_LOW = 12.0;
    private const VOLTAGE_CRITICAL = 10.5;

    /**
     * @param $service_battery
     * @return array
     */
    public function schema($service_battery): array
    {
        return !empty($service_battery) ? [
            'odoo_id'                   => $service_battery['id'],
            'battery_brand_id'          => $service_battery['tire_brand_id'] ?? null,
            'battery_model_id'          => $service_battery['tire_model_id'] ?? null,
            'date_of_purchase'          => $service_battery['date_of_purchase'] ?: null,
            'warranty_date'             => $service_battery['warranty_date'] ?: null,
            'service_id'                => $service_battery['service_id'],
            'amperage'                  => $service_battery['amperage'],
            'alternator_voltage'        => $service_battery['alternator_voltage'],
            'battery_voltage'           => $service_battery['battery_voltage'],
            'status_battery'            => $service_battery['status_battery'],
            'status_alternator'         => $service_battery['status_alternator'],
            'good_condition'            => $service_battery['good_condition'],
            'liquid_leakage'            => $service_battery['liquid_leakage'],
            'corroded_terminals'        => $service_battery['corroded_terminals'],
            'frayed_cables'             => $service_battery['frayed_cables'],
            'inflated'                  => $service_battery['inflated'],
            'cracked_case'              => $service_battery['cracked_case'],
            'new_battery'               => $service_battery['new_battery'],
            'replaced_battery'          => $service_battery['replaced_battery'],
            'serial_product'            => $service_battery['serial_product'] ?? null,
            'starting_current'          => $service_battery['starting_current'],
            'accumulated_load_capacity' => $service_battery['accumulated_load_capacity'],
            'health_status'             => $service_battery['health_status'],
            'health_percentage'         => $service_battery['health_percentage'],
            'health_status_final'       => $this->getHealthStatusFinal(
                $service_battery['health_percentage'],
                $service_battery['battery_voltage']
            ),
            'battery_charged'           => $service_battery['battery_charged'],
            'sequence_id'               => (new ServiceBattery())->incrementSequence()
        ] : [];
    }

    /**
     * Determine the final health status based on health percentage and battery voltage.
     *
     * @param float $healthPercentage
     * @param float $batteryVoltage
     * @return string
     */
    protected function getHealthStatusFinal(float $healthPercentage, float $batteryVoltage): string
    {
        if ($healthPercentage >= self::HEALTH_THRESHOLD) {
            return $this->evaluateVoltage($batteryVoltage, 'Buen estado', 'Requiere carga', 'Dañada');
        }

        return $this->evaluateVoltage($batteryVoltage, 'Deficiente', 'Deficiente', 'Dañada');
    }

    /**
     * Evaluate the battery voltage and return the appropriate status.
     *
     * @param float $voltage
     * @param string $good
     * @param string $low
     * @param string $critical
     * @return string
     */
    private function evaluateVoltage(float $voltage, string $good, string $low, string $critical): string
    {
        if ($voltage > self::VOLTAGE_GOOD) {
            return $good;
        } elseif ($voltage >= self::VOLTAGE_LOW && $voltage <= self::VOLTAGE_GOOD) {
            return $low;
        } elseif ($voltage >= self::VOLTAGE_CRITICAL && $voltage < self::VOLTAGE_LOW) {
            return $low;
        }

        return $critical;
    }
}
