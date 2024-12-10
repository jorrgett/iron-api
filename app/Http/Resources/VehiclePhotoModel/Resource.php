<?php

namespace App\Http\Resources\VehiclePhotoModel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'brand_id'   => $this->brand_id,
            'brand_name' => $this->brands->name,
            'model_id'   => $this->model_id,
            'model_name' => $this->models->name,
            'year'       => $this->year,
            'color'      => $this->color,
            'photo_url'  => $this->photo_url,
            'is_active'  => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
