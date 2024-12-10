<?php

namespace App\Http\Resources\VehicleModel;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleModel\VehicleModelResource;

class VehicleModelCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => VehicleModelResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
