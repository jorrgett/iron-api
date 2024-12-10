<?php

namespace App\Transformers;

use App\Models\ServiceItem;

class ServiceItemsTransformer extends Transformer
{
    /**
     * @param $service_item
     * @return array
     */
    public function schema($service_item):  array
    {
        return [
            'service_id'    => $service_item['service_id'],
            'type'          => $service_item['type'],
            'product_id'    => $service_item['product_id'],
            'display_name'  => $service_item['product_name'],
            'qty'           => $service_item['product_qty'],
            'operator_id'   => $service_item['x_operador_serv_id'] ?? null,
            'odoo_id'       => $service_item['id'],
            'sequence_id'   => (new ServiceItem())->incrementSequence()
        ];
    }
}
