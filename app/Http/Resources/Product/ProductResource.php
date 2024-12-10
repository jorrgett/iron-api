<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'odoo_id'             => $this->odoo_id,
            'name'                => $this->name,
            'otd'                 => $this->otd,
            'life_span'           => $this->life_span,
            'life_span_unit'      => $this->life_span_unit,
            'product_category_id' => $this->product_category_id,
            'sequence_id'         => $this->sequence_id,
        ];
    }
}
