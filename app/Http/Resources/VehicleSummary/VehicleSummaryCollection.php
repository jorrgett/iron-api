<?php

namespace App\Http\Resources\VehicleSummary;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\VehicleSummary\VehicleSummaryResource;

class VehicleSummaryCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => VehicleSummaryResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
