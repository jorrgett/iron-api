<?php

namespace App\Http\Resources\ServiceItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'odoo_id'      => $this->odoo_id,
            'service_id'   => $this->service_id,
            'type'         => $this->type,
            'product_id'   => $this->product_id,
            'display_name' => $this->display_name,
            'qty'          => $this->qty,
            'operator_id'  => $this->operator_id,
            'sequence_id'  => $this->sequence_id
        ];
    }
}
