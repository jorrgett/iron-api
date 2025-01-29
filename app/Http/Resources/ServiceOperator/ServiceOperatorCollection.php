<?php

namespace App\Http\Resources\ServiceOperator;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ServiceOperator\ServiceOperatorResource;

class ServiceOperatorCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceOperatorResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
