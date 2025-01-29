<?php

namespace App\Transformers;

use App\Models\VehicleBrand;

class VehicleBrandTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($vehicle_brand): array
    {
        return [
            'name'        => $vehicle_brand['vehicle']->vehicle_brand_name,
            'url_image'   => null,
            'odoo_id'     => $vehicle_brand['vehicle']->vehicle_brand_id,
            'sequence_id' => (new VehicleBrand())->incrementSequence()
        ];
    }
}
