<?php

namespace App\Transformers;

use App\Models\ServiceTire;

class ServiceTiresTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($service_tires): array
    {
        return [
            'service_id'         => $service_tires['service_id'],
            'location'           => $service_tires['location'],
            'depth'              => $service_tires['depth'],
            'starting_pressure'  => $service_tires['starting_pressure'],
            'finishing_pressure' => $service_tires['finishing_pressure'],
            'dot'                => (int) $service_tires['dot'],
            'tire_brand_id'      => $service_tires['tire_brand_id'] ?? null,
            'tire_model_id'      => $service_tires['tire_model_id'] ?? null,
            'tire_size_id'       => $service_tires['tire_size_id'] ?? null,
            'odoo_id'            => $service_tires['id'],
            'regular'            => $service_tires['regular'],
            'staggered'          => $service_tires['staggered'],
            'central'            => $service_tires['central'],
            'right_shoulder'     => $service_tires['right_shoulder'],
            'left_shoulder'      => $service_tires['left_shoulder'],
            'not_apply'          => $service_tires['not_apply'],
            'bulge'              => $service_tires['bulge'],
            'perforations'       => $service_tires['perforations'],
            'vulcanized'         => $service_tires['vulcanized'],
            'aging'              => $service_tires['aging'],
            'cracked'            => $service_tires['cracked'],
            'deformations'       => $service_tires['deformations'],
            'separations'        => $service_tires['separations'],
            'tire_change'        => $service_tires['tire_change'],
            'depth_original'     => $service_tires['depth_original'],
            'sequence_id'        => (new ServiceTire())->incrementSequence()
        ];
    }
}
