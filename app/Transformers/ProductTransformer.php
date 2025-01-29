<?php

namespace App\Transformers;

use App\Models\Product;

class ProductTransformer extends Transformer
{
    /**
     * @param $product
     * @return array
     */
    public function schema($product): array
    {
        return [
            'name'                => $product['product_name'],
            'otd'                 => (int) $product['otd'],
            'life_span'           => $product['life_span'],
            'life_span_unit'      => $product['life_span_unit'],
            'product_category_id' => $product['product_category_id'],
            'odoo_id'             => $product['product_id'],
            'sequence_id'         => (new Product())->incrementSequence()
        ];
    }
}
