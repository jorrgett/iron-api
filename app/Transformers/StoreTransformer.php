<?php

namespace App\Transformers;

use App\Models\Store;

class StoreTransformer extends Transformer
{
    /**
     * @param $store
     * @return array
     */
    public function schema($store): array
    {
        return [
            'name'        => $store['store']->name,
            'street'      => $store['store']->street,
            'street2'     => $store['store']->street2,
            'city'        => $store['store']->city,
            'state'       => $store['store']->state,
            'country'     => $store['store']->country,
            'phone'       => $store['store']->phone,
            'odoo_id'     => $store['store']->id,
            'latitude_id' => $store['store']->latitude_id,
            'length_id'   => $store['store']->length_id,
            'sequence_id' => (new Store())->incrementSequence()
        ];
    }
    
}