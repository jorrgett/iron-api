<?php

namespace App\Http\Resources\TireSize;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\TireSize\TireSizeResource;

class TireSizeCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => TireSizeResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
