<?php

namespace App\Http\Resources\TireStandar;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\TireStandar\TireStandarResource;

class TireStandarCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => TireStandarResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
