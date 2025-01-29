<?php

namespace App\Http\Resources\ServiceAligment;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceAligment\ServiceAligmentResource;

class ServiceAligmentCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ServiceAligmentResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
