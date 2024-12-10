<?php

namespace App\Transformers;

use App\Models\TireModel;

class TireModelTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($tire_brand): array
    {
        return [
            'name'          => $tire_brand['tire_model_name'],
            'tire_brand_id' => $tire_brand['tire_brand_id'],
            'odoo_id'       => $tire_brand['tire_model_id'],
            'sequence_id'   => (new TireModel())->incrementSequence()
        ];
    }
}
