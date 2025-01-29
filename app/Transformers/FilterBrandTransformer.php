<?php

namespace App\Transformers;

use App\Models\TireBrand;
use Illuminate\Support\Facades\Log;

class FilterBrandTransformer extends Transformer
{
    /**
     * @param $service_oil
     * @return array
     */
    public function schema($service_oil)
    {   
        
        return !empty($service_oil['filter_brand']) ? [
            'name'        => $service_oil['filter_brand'] ?? null,
            'url_image'   => null,
            'odoo_id'     => $service_oil['filter_brand_id'] ?? null,
            'sequence_id' => (new TireBrand())->incrementSequence()
        ] : [];
    }
}
