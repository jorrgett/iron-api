<?php

namespace App\Http\Resources\TireBrand;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\TireBrand\TireBrandResource;

class TireBrandCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => TireBrandResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
