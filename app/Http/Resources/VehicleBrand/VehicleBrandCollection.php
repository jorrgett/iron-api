<?php

namespace App\Http\Resources\VehicleBrand;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleBrand\VehicleBrandResource;

class VehicleBrandCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => VehicleBrandResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
