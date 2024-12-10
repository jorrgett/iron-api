<?php

namespace App\Http\Resources\PrivacyTermsConditions;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PrivacyTermsConditionsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
