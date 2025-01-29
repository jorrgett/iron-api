<?php

namespace App\Http\Resources\Odometer;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\Odometer\OdometerResource;

class OdometerCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => OdometerResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
