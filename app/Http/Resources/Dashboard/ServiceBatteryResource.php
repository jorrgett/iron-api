<?php

namespace App\Http\Resources\Dashboard;

use App\Helpers\ParametersHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBatteryResource extends JsonResource
{
    protected $dates;
    protected $params;


    public function __construct($resource, $dates)
    {
        parent::__construct($resource);
        $this->dates = $dates;
        $this->params = new ParametersHelper();
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $healthStatus = str_replace('%   ', '', $this->health_status ?? null);
        if ($healthStatus === 'Deficiente - Reemplazar') {
            $healthStatus = 'Reemplazar';
        }

        $statusBuenEstado = explode(',', $this->params->get_app_parameters('battery_good_condition_color'));
        $statusProximoReemplazo = explode(',', $this->params->get_app_parameters('battery_nearing_replacement_color'));
        $statusReemplazar = explode(',', $this->params->get_app_parameters('battery_replace_color'));
        $statusDañada = explode(',', $this->params->get_app_parameters('battery_damaged_color'));

        $statusSettings = [
            'Buen estado' => $statusBuenEstado,
            'Próximo a Reemplazo' => $statusProximoReemplazo,
            'Reemplazar' => $statusReemplazar,
            'Dañada' => $statusDañada,
        ];

        [$initialGradient, $finalGradient, $backgroundColor] = $statusSettings[$healthStatus] ?? ['#000000', '#000000', '#000000'];

        $rechargeChart = $this->dates ?? [];

        $oldestYear = null;
        if (!empty($rechargeChart)) {
            $oldestYear = Carbon::createFromFormat('Y-m-d', $rechargeChart[0])->year;

            foreach ($rechargeChart as $date) {
                $year = Carbon::createFromFormat('Y-m-d', $date)->year;
                $oldestYear = min($oldestYear, $year);
            }
        }

        return [
            'id'                        => $this->id ?? null,
            'health_percentage'         => $this->health_percentage ?? null,
            'health_status'             => $healthStatus,
            'health_initial_gradient'   => $initialGradient,
            'health_final_gradient'     => $finalGradient,
            'health_final_background'   => $backgroundColor,
            'frayed_cables'             => $this->frayed_cables ?? null,
            'cracked_case'              => $this->cracked_case ?? null,
            'inflated'                  => $this->inflated ?? null,
            'good_condition'            => $this->good_condition ?? null,
            'liquid_leakage'            => $this->liquid_leakage ?? null,
            'corroded_terminals'        => $this->corroded_terminals ?? null,
            'warranty_expiration_date'  => $this->warranty_date ?? null,
            'recharge_chart_begin_year' => $oldestYear,
            'recharge_chart_end_year'   => max($oldestYear + 1, null),
            'recharge_chart_data'       => $rechargeChart,
            'recharge_count'            => count($rechargeChart),
        ];
    }
}
