<?php

namespace App\Transformers;

use App\Models\Odometer;

class OdometerTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($odometer): array
    {

        return [
            'vehicle_id'  => $odometer['odometer']->vehicle_id,
            'driver_id'   => $odometer['odometer']->driver_id,
            'date'        => $odometer['odometer']->date,
            'value'       => $odometer['odometer']->value,
            'odoo_id'     => $odometer['odometer']->id,
            'sequence_id' => (new Odometer())->incrementSequence()
        ];
    }
}
