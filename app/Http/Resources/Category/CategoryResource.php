<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'action_id'   => $this->action_id,
            'parent_id'   => $this->parent_id,
            'code'        => $this->code,
            'parent_code' => $this->categories->code ?? null
        ];
    }
}
