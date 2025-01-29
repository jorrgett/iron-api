<?php

namespace App\Http\Resources\ServiceOil;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceOil\ServiceOilResource;

class ServiceOilCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceOilResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
