<?php

namespace App\Http\Resources\AppWarning;

use App\Http\Resources\ApiCollection;
use Illuminate\Http\Request;
use App\Http\Resources\AppWarning\AutoHealingResource;

class AutoHealingCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => AutoHealingResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
