<?php

namespace App\Http\Resources\Dashboard;

use App\Helpers\ParametersHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBalancingResource extends JsonResource
{
    protected $params;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->params = new ParametersHelper();
        $lead_unit = $this->params->get_app_parameters('lead_unit');
        
        return [
            'id'                        => $this->id,
            'wheel_good_state'          => $this->wheel_good_state,
            'wheel_scratched'           => $this->wheel_scratched,
            'wheel_cracked'             => $this->wheel_cracked,
            'wheel_bent'                => $this->wheel_bent,
            'lead_used'                 => $this->lead_used,
            'lead_unit'                 => $lead_unit,
            'balanced'                  => $this->lead_used != 0 ? true : false,
            'location'                  => $this->location,
        ];
    }
}
