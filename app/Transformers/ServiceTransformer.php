<?php

namespace App\Transformers;

use App\Models\Service;

class ServiceTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($service): array
    {
        return [
            'store_id'        => $service['store']->id,
            'driver_id'       => $service['driver_id'] ?? null,
            'owner_id'        => $service['owner_id'] ?? null,
            'vehicle_id'      => $service['vehicle']->id,
            'date'            => $service['date'],
            'odometer'        => $service['odometer']->value,
            'odometer_id'     => $service['odometer']->id,
            'state'           => $service['state'],
            'driver_name'     => $service['driver_name'],
            'owner_name'      => $service['owner_name'],
            'rotation_x'      => $service['rotation_x'],
            'rotation_lineal' => $service['rotation_lineal'],
            'odoo_id'         => $service['id'],
            'sequence_id'     => (new Service())->incrementSequence()
        ];
    }
}
