<?php

namespace App\Http\Resources\ProductCategory;

use Illuminate\Http\Request;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\ProductCategory\ProductCategoryResource;

class ProductCategoryCollection extends ApiCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => ProductCategoryResource::collection($this->collection),
            'meta'  => $this->dataMeta($request),
            'links' => $this->dataLinks($request)
        ];
    }
}
