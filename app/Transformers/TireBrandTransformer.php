<?php

namespace App\Transformers;

use App\Models\TireBrand;

class TireBrandTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($tire_brand): array
    {
        return [
            'name'        => $tire_brand['tire_brand_name'],
            'url_image'   => null,
            'odoo_id'     => $tire_brand['tire_brand_id'],
            'sequence_id' => (new TireBrand())->incrementSequence()
        ];
    }
}
