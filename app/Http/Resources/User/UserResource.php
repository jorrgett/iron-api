<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'full_name'               => $this->full_name,
            'email'                   => $this->email,
            'avatar_url'              => $this->avatar_url,
            'res_partner_id'          => $this->res_partner_id,
            'res_partner_id_arr'      => $this->contacts->pluck('odoo_id'),
            'country_code'            => $this->country_code,
            'phone'                   => $this->phone,
            'language'                => $this->language,
            'email_verified'          => $this->email_verified,
            'phone_verified'          => $this->phone_verified,
            'legals_accepted'         => $this->legals_accepted,
            'terms_and_conditions_id' => $this->terms_and_conditions_id,
            'legal_disclaimer_id'     => $this->legal_disclaimer_id,
            'privacy_policy_id'       => $this->privacy_policy_id,
            'created_at'              => $this->created_at->format('Y-m-d H:m:s'),
            'updated_at'              => $this->updated_at->format('Y-m-d H:m:s'),
            'roles'                   => $this->roles,
        ];
    }
}