<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'res_partner_id' => $this->contacts->pluck('odoo_id'),
            'full_name' => $this->full_name,
            'email' => $this->email,
            'vehicle_id' => $this->vehicle_id,
            'plate' => $this->plate,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand_name,
            'model_id' => $this->model_id,
            'model_name' => $this->model_name
        ];
    }
}
