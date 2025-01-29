<?php

namespace App\Transformers;

use App\Models\ServiceOil;

class ServiceOilTransformer extends Transformer
{
    /**
     * @param $service_oil
     * @return array
     */
    public function schema($service_oil): array
    {
        return [
            'odoo_id'         => $service_oil['id'],
            'service_id'      => $service_oil['service_id'],
            'tire_brand_id'   => $service_oil['tire_brand_id'],
            'oil_viscosity'   => $service_oil['oil_viscosity'],
            'type_oil'        => $service_oil['type_oil'],
            'life_span'       => $service_oil['life_span'],
            'oil_quantity'    => $service_oil['oil_quantity'],
            'filter_brand_id' => $service_oil['filter_brand_id'] != false ? $service_oil['filter_brand_id'] : null,
            'sequence_id'   => (new ServiceOil())->incrementSequence()
        ];
    }
}
