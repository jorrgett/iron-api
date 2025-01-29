<?php

namespace App\Transformers;

use App\Models\Store;
use App\Models\InspectionFluid;
use Illuminate\Support\Facades\Log;

class InspectionFluidTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function schema($item): array
    {   
        return !empty($item['inspection_fluid']) > 0 ? [
            'odoo_id'               => $item['inspection_fluid']->id,
            'service_id'            => $item['inspection_fluid']->service_id,
            'transmission_case_oil' => $item['inspection_fluid']->transmission_case_oil,
            'transfer_oil'          => $item['inspection_fluid']->transfer_oil,
            'gear_box_oil'          => $item['inspection_fluid']->gear_box_oil,
            'engine_coolant'        => $item['inspection_fluid']->engine_coolant,
            'brake_fluid'           => $item['inspection_fluid']->brake_fluid,
            'engine_oil'            => $item['inspection_fluid']->engine_oil,
            'brake_league'          => $item['inspection_fluid']->brake_league,
            'cleaning_liquid'       => $item['inspection_fluid']->cleaning_liquid,
            'fuel_tank'             => $item['inspection_fluid']->fuel_tank,
            'steering_oil'          => $item['inspection_fluid']->steering_oil,
            'front_diff_oil'        => $item['inspection_fluid']->front_diff_oil,
            'rear_diff_oil'         => $item['inspection_fluid']->rear_diff_oil,
            'sequence_id' => (new InspectionFluid())->incrementSequence()
        ] : [];
    }
}