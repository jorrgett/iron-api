<?php

namespace App\Http\Resources\TireSize;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TireSizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'odoo_id'     => $this->odoo_id,
            'name'        => $this->name,
            'sequence_id' => $this->sequence_id,
        ];
    }
}
