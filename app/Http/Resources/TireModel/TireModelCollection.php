<?php

namespace App\Http\Resources\TireModel;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\TireModel\TireModelResource;

class TireModelCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => TireModelResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
