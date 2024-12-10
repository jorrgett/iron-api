<?php

namespace App\Http\Resources\VehiclePhotoModel;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\VehiclePhotoModel\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class Collection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['data' => Resource::collection($this->collection)];
    }
}
