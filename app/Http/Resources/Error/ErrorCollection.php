<?php

namespace App\Http\Resources\Error;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\Error\ErrorResource;

class ErrorCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ErrorResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
