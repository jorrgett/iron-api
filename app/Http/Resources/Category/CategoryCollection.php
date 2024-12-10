<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\Category\CategoryResource;

class CategoryCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => CategoryResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
