<?php

namespace App\Http\Resources\UserList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user_id'       => $this->drivers->id,
            'vehicle_id'    => $this->vehicles->id,
            'name'          => $this->drivers->full_name,
            'email'         => $this->drivers->email,
            'phone'         => $this->drivers->country_code . $this->drivers->phone
        ];
    }
}
