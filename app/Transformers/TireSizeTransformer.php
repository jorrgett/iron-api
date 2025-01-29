<?php

namespace App\Transformers;

use App\Models\TireSize;

class TireSizeTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($tire_brand): array
    {
        return [
            'name'     => $tire_brand['tire_size_name'],
            'odoo_id'  => $tire_brand['tire_size_id'],
            'sequence_id' => (new TireSize())->incrementSequence()
        ];
    }
}
