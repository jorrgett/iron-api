<?php

namespace App\Http\Resources\TireOemDepth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TireOemDepthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'tire_brand_id' => $this->tire_brand_id,
            'tire_model_id' => $this->tire_model_id,
            'tire_size_id'  => $this->tire_size_id,
            'otd'           => $this->otd,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
