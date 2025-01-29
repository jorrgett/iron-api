<?php

namespace App\Transformers;

use App\Models\VehicleModel;

class VehicleModelTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($vehicle_model): array
    {
        return [
            'name'             => $vehicle_model['vehicle']->vehicle_model_name,
            'vehicle_brand_id' => $vehicle_model['vehicle']->vehicle_brand_id,
            'odoo_id'          => $vehicle_model['vehicle']->vehicle_model_id,
            'sequence_id' => (new VehicleModel())->incrementSequence()
        ];
    }
}
