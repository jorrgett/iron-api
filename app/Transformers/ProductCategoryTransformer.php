<?php

namespace App\Transformers;

use App\Models\ProductCategory;

class ProductCategoryTransformer extends Transformer
{
    /**
     * @param $product
     * @return array
     */
    public function schema($product): array
    {
        return [
            'name'        => $product['product_category_name'],
            'odoo_id'     => $product['product_category_id'],
            'sequence_id' => (new ProductCategory())->incrementSequence()
        ];
    }
}
