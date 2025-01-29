<?php

namespace App\Http\Resources\StoreServices;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreServicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'odoo_id'               => $this->odoo_id,
            'name'                  => $this->name,
            'street'                => $this->street,
            'street2'               => $this->street2,
            'city'                  => $this->city,
            'state'                 => $this->state,
            'country'               => $this->country,
            'phone'                 => $this->phone,
            'sequence_id'           => $this->sequence_id,
            'latitude'              => $this->latitude,
            'longitude'             => $this->longitude,
            'photo_url'             => $this->photo_url,
            'photo_path'            => $this->photo_path,
            'is_active'             => $this->is_active,
            'store_score'           => $this->store_score,
            'feedback_quantity'     => $this->feedback_quantity,
            'available_services'    => $this->availableServices
        ];
    }
}
