<?php

namespace App\Helpers;

use App\Models\InspectionFluid;
use App\Models\MaintenanceSchedule;
use App\Models\Service;
use App\Models\ServiceAligment;
use App\Models\ServiceBattery;
use App\Models\ServiceOil;
use App\Models\ServiceTire;
use App\Models\Store;
use App\Models\Vehicle;
use App\Models\VehicleTireHistory;
use App\Models\VehicleTireSummary;
use App\Repositories\Vehicle\VehicleRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DashboardHelper
{
    protected $params;
    protected $vehicleRepository;
    protected $parameters;
    protected $odometer;
    protected $rotation_lineal = [
        'Delantero Izquierdo' => 'Trasero Izquierdo',
        'Trasero Izquierdo' => 'Delantero Izquierdo',
        'Delantero Derecho' => 'Trasero Derecho',
        'Trasero Derecho' => 'Delantero Derecho'
    ];
    protected $rotation_response_lineal = [
        'show_arrows_left'  => true,
        'show_arrows_rigth' => true,
        'show_arrows_cross' => false,
    ];
    protected $rotation_x = [
        'Delantero Izquierdo' => 'Trasero Derecho',
        'Trasero Izquierdo'   => 'Delantero Derecho',
        'Delantero Derecho'   => 'Trasero Izquierdo',
        'Trasero Derecho'     => 'Delantero Izquierdo'
    ];
    protected $rotation_response_x =  [
        'show_arrows_left'  => false,
        'show_arrows_rigth' => false,
        'show_arrows_cross' => true,
    ];
    private $increment_km_base = 5000;

    /**
     * VehicleController Constructor.
     *
     * @param VehicleRepository $vehicleRepository
     */

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->params = new ParametersHelper();
        $this->vehicleRepository = $vehicleRepository;
        $this->parameters = [
            'size' => 100
        ];
    }

    /**
     * Get Oil Chart
     *
     */
    public function get_oil_chart($vehicle_id = null)
    {
        if ($vehicle_id == null) {
            $ownVehicles = $this->vehicleRepository->queryGetAll(1, 10)->get();
            $groupByVehicle = $this->oilGroupByVehicle($ownVehicles);
        } else {
            $ownVehicles = $this->vehicleRepository->getByField('odoo_id', $vehicle_id);
            $groupByVehicle = $this->oilGroupByVehicle($ownVehicles);
        }

        $data = [];
        foreach ($groupByVehicle as $key => $value) {
            $data = array_merge($data, $this->calculate_oil_change($value));
        }

        return $data;
    }

    protected function calculate_oil_change($items)
    {
        $data = new Collection($items);
        $dataWithoutZero = $data->whereNotIn('km_traveled', 0);
        $avg_days = round($dataWithoutZero->avg('days_passed'), 0, PHP_ROUND_HALF_UP);
        $last_item = ($dataWithoutZero->last());
        $last_item_withZero = $data->last();
        $last_life_span = !is_null($last_item)
            ? $last_item->life_span
            : ($data->last()->life_span);

        $result = $this->vehicleOilMapping($data, $last_life_span, $avg_days);
        $proyected_items = [];

        if (count($result) < 7) {
            $i = 1;
            do {
                $item_last_date = $last_item->change_date ?? $last_item_withZero->change_date;
                $item_last_change_next_date = $last_item->change_next_days ?? $last_item_withZero->change_next_days;
                $last_date = $proyected_items[$i - 1]['change_date'] ?? null;
                $new_date = is_null($last_date) ? ((Carbon::createFromFormat('Y-m-d', $item_last_date))->addDay($item_last_change_next_date))->format('Y-m-d') : (Carbon::createFromFormat('Y-m-d', $last_date)->addDay($avg_days))->format('Y-m-d');
                $day_passed = is_null($last_date) ? $item_last_change_next_date : $avg_days;

                $proyected_items[] = [
                    'vehicle_id'       => $last_item->vehicle_id ?? $last_item_withZero->vehicle_id,
                    'days_passed'      => $day_passed,
                    'km_traveled'      => $last_item->km_proyected ?? $last_item_withZero->life_span,
                    'change_date'      => $new_date,
                    'last_life_span'   => $last_life_span,
                    'avg_days'         => $avg_days ?? 0,
                    'data_type'        => 'p',
                    'data_color'       => '#F57329'
                ];

                $i++;
            } while ($i <= 1);
        }

        return ($result->merge($proyected_items))->toArray();
    }

    private function vehicleOilMapping($data, $last_life_span, $avg_days)
    {
        return $data->map(function ($field) use ($last_life_span, $avg_days) {
            return [
                'vehicle_id'       => $field->vehicle_id,
                'days_passed'      => $field->days_passed,
                'km_traveled'      => $field->km_traveled,
                'change_date'      => $field->change_date,
                'change_next_days' => $field->change_next_days,
                'last_life_span'   => $last_life_span,
                'avg_days'         => $avg_days ?? 0,
                'data_type'        => 'r',
                'data_color'       => '#0B3DEF'
            ];
        });
    }

    protected function oilGroupByVehicle($ownVehicles)
    {
        $groupByVehicle = [];

        foreach ($ownVehicles as $vehicle) {
            $getViewVehicleOilChart =
                DB::select(
                    "Select * from datato_vehicle_oil_chart where vehicle_id = {$vehicle->odoo_id}"
                );

            if ($getViewVehicleOilChart) {
                $groupByVehicle[$vehicle->odoo_id] = $getViewVehicleOilChart;
            }
        }

        return $groupByVehicle;
    }

    /**
     * Get Tire chart
     *
     */
    public function getVehicleTireChart($vehicle_id)
    {
        $groupByService = $this->tiresGroupByService($vehicle_id);

        $data = [];
        foreach ($groupByService as $key => $value) {
            $data = array_merge($data, $this->calculate_tire_chart($value));
        }

        return $data;
    }

    protected function calculate_tire_chart($item)
    {
        $data = new Collection($item);
        $result = $this->dataTireMapping($data);
        $prev = $result->toArray();

        $set = 1;

        for ($i = 0; $i < count($prev); $i++) {
            $prev[$i]['date_estimated'] = ((Carbon::createFromFormat('Y-m-d', $prev[$i]['service_date']))->addDay(round((30 * $prev[$i]['months_between_visits'])), 0, PHP_ROUND_HALF_UP))->format('Y-m-d');
            $prev[$i]['index_visit'] = $set++;
        }

        return $prev;
    }

    private function dataTireMapping($data)
    {
        $i = 0;
        return $data->map(function ($field) {
            $lifespan_consumed = ($field['lifespan_consumed'] * 100);
            switch (true) {
                case $lifespan_consumed <= 75:
                    $description = 'Buen Estado';
                    $initial_gradient = '#25D073';
                    $final_gradient = '#46CC34';
                    $final_background = '#DDFEDC';
                    break;
                case $lifespan_consumed <= 85:
                    $description = 'Requiere cambio';
                    $initial_gradient = '#F56D21';
                    $final_gradient = '#CA0000';
                    $final_background = '#FDCDCD';
                    break;
                default:
                    $description = 'Peligro';
                    $initial_gradient = '#717171';
                    $final_gradient = '#252525';
                    $final_background = '#D9D9D9';
                    break;
            }


            return [
                'service_id'                   => $field['service_id'],
                'service_date'                 => $field['service_date'],
                'vehicle_id'                   => $field['vehicle_id'],
                'tire_location'                => $field['tire_location'],
                'odometer'                     => $field['odometer'],
                'otd'                          => (float) $field['otd'],
                'tread_depth'                  => (float) $field['tread_depth'],
                'km_traveled'                  => $field['km_traveled'],
                'km_traveled_acum'             => $field['accum_km_traveled'],
                'mm_consumed'                  => $field['mm_consumed'],
                'performance_index'            => $field['performance_index'],
                'km_proyected'                 => $field['km_proyected'],
                'odometer_estimated'           => $field['odometer_estimated'],
                'safe_depth'                   => $field['safe_depth'],
                'lifespan_consumed'            => $lifespan_consumed,
                'months_between_visits'        => $field['months_between_visits'],
                'data_description_lifespan_consumed' => $description,
                'lifespan_consumed_initial_gradient' => $initial_gradient,
                'lifespan_consumed_final_gradient' => $final_gradient,
                'lifespan_consumed_final_background' => $final_background,
                'plot_data_type'               => 'R',
                "data_color"                   => "#0B3DEF",
                "data_color_lifespan_consumed" => "#0BEF22"
            ];
        });
    }

    protected function tiresGroupByService($ownVehicles)
    {
        $getTireHistories = VehicleTireHistory::where('vehicle_tire_histories.vehicle_id', $ownVehicles)
            ->join('services', 'vehicle_tire_histories.service_id', '=', 'services.odoo_id')
            ->where('services.state', 'done')
            ->where('tire_location', '<>', 'Repuesto')
            ->orderBy('service_date', 'asc')
            ->get()
            ->toArray();


        $groupByVehicle = [];

        foreach ($getTireHistories as $vehicle) {
            $vehicleTireSummaries = VehicleTireSummary::select(
                'vehicle_id',
                'tire_location',
                'prom_tire_km_month',
                'prom_tire_mm_x_visit',
                'months_to_tire_unsafe',
                'projected_tire_visits',
                'estimated_months_tire_visits',
                'accum_km_traveled',
                'accum_days_total',
                'life_span_consumed'
            )->where('vehicle_id', $vehicle['vehicle_id'])->where('tire_location', $vehicle['tire_location'])->first();

            if (!empty($vehicleTireSummaries)) {
                $groupByVehicle[$vehicle['tire_location']][] = array_merge($vehicle, $vehicleTireSummaries->toArray());
            }
        }

        return $groupByVehicle;
    }

    /**
     * Service Dashboard chart
     *
     */
    public function get_service_chart($vehicle_id, $res_partner)
    {
        $vehicle = Vehicle::where('odoo_id', $vehicle_id)->first();
        $balancing_min = $this->params->get_app_parameters('balancing_min');
        $balancing_max = $this->params->get_app_parameters('balancing_max');
        $alignment_min = $this->params->get_app_parameters('alignment_min');
        $alignment_max = $this->params->get_app_parameters('alignment_max');
        $min_alert = $this->params->get_app_parameters('min_show_change_alert_message');
        $max_alert = $this->params->get_app_parameters('max_show_change_alert_message');
        $danger = $this->params->get_app_parameters('show_danger_message');

        if (!$vehicle) {
            return ['Dashboard' => []];
        }

        $service = Service::where('vehicle_id', $vehicle->odoo_id)
            ->where('state', 'done')
            ->orderBy('date', 'desc')
            ->first();

        if ($service) {
            $store = Store::where('odoo_id', $service->store_id)->first();
            $this->odometer = $service->odometer;
            $last_service_date = Carbon::parse($service->date);

            $maintenance = MaintenanceSchedule::where('vehicle_id', $vehicle->odoo_id)
                ->where('status', 'scheduled')
                ->orderBy('order', 'desc')
                ->first();

            if ($maintenance) {
                $next_maintenance_date = $maintenance->created_at->addDays($maintenance->maintenance_interval)->format('Y-m-d');
            }

            if ($service->driver_id == $res_partner && $service->driver_score) {
                $score = true;
            } else if ($service->owner_id == $res_partner && $service->owner_score) {
                $score = true;
            } else {
                $score = false;
            }
        } else {
            $this->odometer = null;
            $last_service_date = null;
            $score = null;
            $next_maintenance_date = null;
        }

        $last_battery = Service::where('vehicle_id', $vehicle->odoo_id)
            ->where('state', 'done')
            ->has('serviceBattery')
            ->with(['serviceBattery' => function ($query) {
                $query->latest('created_at');
            }])
            ->latest('date')
            ->first();

        if ($last_battery) {
            $battery = ServiceBattery::where('service_id', $last_battery->odoo_id)->first();
        }

        $last_balancing = Service::whereHas('serviceBalancing', function ($query) {
            $query->where('balanced', true);
            $query->where('state', 'done');
        })->where('vehicle_id', $vehicle_id)->orderBy('date', 'desc')->first();

        $balanceo_count = Service::whereHas('serviceBalancing', function ($query) {
            $query->where('balanced', true);
            $query->where('state', 'done');
        })->where('vehicle_id', $vehicle_id)->count();

        $tire_summaries = VehicleTireSummary::where('vehicle_id', $vehicle_id)
            ->where('tire_location', '<>', 'Repuesto')
            ->orderBy('life_span_consumed', 'desc')
            ->take(1)
            ->get();

        $tire_location = $tire_summaries->first()->tire_location ?? 'Delantero Izquierdo';

        $tire_histories = VehicleTireHistory::where('vehicle_id', $vehicle_id)
            ->where('tire_location', $tire_location)
            ->orderBy('service_date')
            ->get();

        if ($tire_histories !== null && $tire_histories->count() > 0) {
            $first_history = $tire_histories->first();
            $last_history = $tire_histories->last();

            $kms_next = 0;
            $percentage_km_traveled = 0;

            if ($tire_histories->last()->odometer != $tire_histories->first()->odometer && $tire_summaries->first()->prom_tire_km_month != 0) {
                $kms_traveled = $last_history->odometer - $first_history->odometer;
                $projected_days = $last_history->km_proyected / ($tire_summaries->first()->prom_tire_km_month / 30);

                $kms_next = $last_history->odometer_estimated;
                $date_next = Carbon::parse($tire_histories->last()->service_date)->addDays($projected_days)->format('Y-m-d');

                $percentage_km_traveled = round(($kms_traveled / ($kms_next - $tire_histories->first()->odometer)) * 100, 0);
            }
        }

        $tire_details = $this->getVehicleTireChar($vehicle_id);

        $max_lifespan_consumed = 0;
        foreach ($tire_details['tires_data'] as $tire) {
            if ($tire['lifespan_consumed'] > $max_lifespan_consumed) {
                $max_lifespan_consumed = $tire['lifespan_consumed'];
            }
        }

        $last_alignment = Service::whereHas('aligment', function ($query) {
            $query->where('state', 'done');
        })->where('vehicle_id', $vehicle_id)->orderBy('date', 'desc')->first();

        $alignment_count = Service::whereHas('aligment', function ($query) {
            $query->where('state', 'done');
        })->where('vehicle_id', $vehicle_id)->count();

        $data_balancing = $last_balancing ? [
            'balancing_label' => 'Balanceo',
            'kms_current' => (int) $last_balancing->odometer,
            'date_current' => $last_balancing->date,
            'kms_next' => (int) $last_balancing->odometer + $balancing_max,
            'date_next' => optional(Carbon::create($last_balancing->date))->addMonths(6)->format('Y-m-d'),
            'percentage_km_traveled' => $service && $last_balancing ?
                round(($service->odometer - (int) $last_balancing->odometer) / $balancing_max * 100, 0) :
                null,
            'percentage_km_traveled_color' => ($service->odometer -  (int) $last_balancing->odometer) < $balancing_min ? '#46CA31' : '#CC0D0D',
            'percentage_kms_good_max' => round($balancing_min / $balancing_max * 100), // al cumplir 5000 kms se solicita balanceo que como maximo se debe realizar a los 8000 kms
            'percentage_date_good_max' => 63,
            'percentage_date_good_color' => '#46CA31',
            'percentage_date_bad_color' => '#CC0D0D',
            'no_data_kms' => false,
            'no_data_dates' => $balanceo_count >= 1 ? false : true,
        ] : [
            'balancing_label' => 'Balanceo',
            'no_data_kms' => true,
            'no_data_dates' => true,
        ];

        $alignment_data = $last_alignment ? [
            'alignment_label' => 'Alineación',
            'kms_current' => (int) $last_alignment->odometer,
            'date_current' => $last_alignment->date,
            'kms_next' => (int) $last_alignment->odometer + $alignment_max,
            'date_next' => optional(Carbon::create($last_alignment->date))->addMonths(6)->format('Y-m-d'),
            'percentage_km_traveled' => $service && $last_alignment ?
                round(($service->odometer - (int) $last_alignment->odometer) / $alignment_max * 100, 0) :
                null,
            'percentage_km_traveled_color' => ($service->odometer -  (int) $last_alignment->odometer) < $alignment_min ? '#46CA31' : '#CC0D0D',
            'percentage_kms_good_max' => round($alignment_min / $alignment_max * 100),
            'percentage_date_good_max' => 70,
            'percentage_date_good_color' => '#46CA31',
            'percentage_date_bad_color' => '#CC0D0D',
            'no_data_kms' => false,
            'no_data_dates' => $alignment_count >= 1 ? false : true,
        ] : [
            'alignment_label' => 'Alineación',
            'no_data_kms' => true,
            'no_data_dates' => true,
        ];

        $data_battery = $last_battery ? [
            'battery_label' => 'Batería',
            'battery_brand_name' => $battery->tire_brands->name,
            'amperage' => $battery->amperage,
            'unit_amperage' => null,
            'starting_current' => $battery->starting_current,
            'unit_starting_current' => 'CA',
            'battery_voltage' => $battery->battery_voltage,
            'unit_voltage' => 'V',
            'alternator_voltage' => $battery->alternator_voltage,
            'final_health_good_state' => $battery->health_status_final === 'Buen estado',
            'final_heath_charge_required' => $battery->health_status_final === 'Requiere carga',
            'final_heath_deficient' => $battery->health_status_final === 'Deficiente',
            'final_heath_damaged' => $battery->health_status_final === 'Dañada',
            'no_data' => false,
        ] : [
            'battery_label' => 'Batería',
            'no_data' => true,
        ];

        $tire_data = [
            'tire_label' => 'Rendimiento de neumáticos',
            'kms_current' => $tire_histories->first()->odometer ?? null,
            'date_current' => $tire_histories->last()->service_date ?? null,
            'average_lifespanconsumed' => $max_lifespan_consumed,
            'show_change_alert_message' => $max_lifespan_consumed >= $min_alert && $max_lifespan_consumed < $max_alert,
            'show_danger_message' => $max_lifespan_consumed >= $danger,
            'no_data_current' => count($tire_histories) < 1,
            'no_data_next' => count($tire_histories) < 3,
        ];

        if (count($tire_histories) >= 3 && $tire_summaries->first()->prom_tire_km_month != 0) {
            $tire_data['kms_current'] = $tire_histories->first()->odometer;
            $tire_data['date_current'] = $tire_histories->last()->service_date;
            $tire_data['kms_next'] = $kms_next;
            $tire_data['date_next'] = $date_next ?? $tire_data['date_current'];
            $tire_data['percentage_km_traveled'] = $percentage_km_traveled;
            $tire_data['percentage_km_traveled_color'] = $percentage_km_traveled <= 75 ? '#46CA31' : '#CC0D0D';
            $tire_data['percentage_date_good_max'] = 75;
            $tire_data['percentage_kms_good_max'] = 75; // cuando los kms recorridos sea mayor o igual al 75% de los kms proyectados se debe realizar el cambio de neumáticos
            $tire_data['percentage_date_good_color'] = '#46CA31';
            $tire_data['percentage_date_bad_color'] = '#CC0D0D';
            $tire_data['average_lifespanconsumed'] = $max_lifespan_consumed;
            $tire_data['average_next_change_tires'] = 40;
            $tire_data['lifespan_initial_gradient'] = '#FFC837';
            $tire_data['lifespan_final_gradient'] = '#FF4308';
            $tire_data['next_change_tires_initial_gradient'] = '#08D3FF';
            $tire_data['next_change_tires_final_gradient'] = '#CB37FF';
        }

        return [
            'Dashboard' => [
                'vehicle_id' => $vehicle_id,
                'vehicle_nickname' => $vehicle->nickname,
                'vehicle_plate' => $vehicle->plate,
                'vehicle_brand_id' => $vehicle->vehicle_brand_id,
                'vehicle_brand_name' => $vehicle->vehicle_brands->name,
                'vehicle_model_id' => $vehicle->vehicle_model_id,
                'vehicle_model_name' => $vehicle->vehicle_models->name,
                'vehicle_odometer_unit' => $vehicle->odometer_unit,
                'odometer' => (int) $this->odometer,
                'last_service_date' => $last_service_date,
                'last_service_id' => $service->odoo_id ?? null,
                'last_store_id' => $service->store_id ?? null,
                'last_store_name' => $store->name ?? null,
                'scored' => $score,
                'next_maintenance_date' => $next_maintenance_date,
                'next_maintenance_kms' => $maintenance->maintenance_kms ?? null,
                'battery_data' => $data_battery,
                'balancing_data' => $data_balancing,
                'alignment_data' => $alignment_data,
                'tire_data' => $tire_data,
                'oil_data'  => $this->getOilStats($vehicle_id),
                'rotation_data' => $this->getRotationStats($vehicle_id, $service),
                'fluids_data' => $this->getFluidStats($vehicle_id),
            ],
        ];
    }

    public function get_oil_change_details(int $vehicle_id)
    {
        $count_oil_changes = $this->params->get_app_parameters('min_service_oil_changes');

        $last_service_oil = ServiceOil::join('services', 'service_oil.service_id', '=', 'services.odoo_id')
            ->where('services.state', 'done')
            ->where('services.vehicle_id', $vehicle_id)
            ->orderByDesc('date')
            ->first();

        if ($last_service_oil) {
            $service_item = DB::select("SELECT * FROM services_oil_complete WHERE vehicle_id = $vehicle_id");

            $format_type_oil = [
                'diesel'         => 'Diesel',
                'synthetic'      => 'Sintético',
                'mineral'        => 'Mineral',
                'semi_synthetic' => 'Semi Sintético'
            ];

            $get_oil_chart = $this->get_oil_chart($last_service_oil->vehicle_id);
            $oil_change_services = [];

            for ($i = 0; $i < count($get_oil_chart); $i++) {
                unset($get_oil_chart[$i]['vehicle_id']);
                unset($get_oil_chart[$i]['data_type']);
                $get_oil_chart[$i]['visit_index'] = $i + 1;
                $oil_change_services[] = $get_oil_chart[$i];
            }


            return !empty($service_item) ? [
                'oil_change_data' => [
                    'oil_card' => [
                        'card_title' => 'Aceite',
                        'oil_name' => $service_item[0]->display_name ?? null,
                        'oil_viscosity' => $service_item[0]->oil_viscosity ?? null,
                        'oil_brand' => $service_item[0]->brand_name ?? null,
                        'oil_type' => $format_type_oil[$service_item[0]->type_oil] ?? null,
                        'oil_quantity' => (string) $service_item[0]->qty ?? 0,
                        'oil_unit' => 'Litros',
                    ],
                    'filter_card' => [
                        'card_title' => 'Filtro',
                        'filter_name' => $service_item[0]->filter_name ?? null,
                        'filter_brand_name' => $service_item[0]->filter_brand_name ?? null
                    ],
                    'no_data_chart' => count($get_oil_chart) < ($count_oil_changes + 1) ? true : false,
                    'oil_change_services' => count($get_oil_chart) < ($count_oil_changes + 1) ? [] : $oil_change_services
                    /**
                 * count_oil_changes corresponde a la cantidad mínima de servicios de cambios de aceite para mostrar gráficos
                 * y proyección de fecha de próximo cambio, se le adiciona uno correspondiente al servicio proyectado
                 */
                ]
            ] : ['oil_change_data' => []];
        } else {
            return [
                'oil_change_data' => []
            ];
        }
    }

    private function getOilStats($vehicle_id)
    {
        $last_service_oil = ServiceOil::join('services', 'service_oil.service_id', '=', 'services.odoo_id')
            ->where('services.state', 'done')
            ->where('services.vehicle_id', $vehicle_id)
            ->orderByDesc('date')
            ->first();

        $cant_service_oil = ServiceOil::join('services', 'service_oil.service_id', '=', 'services.odoo_id')
            ->where('services.state', 'done')
            ->where('services.vehicle_id', $vehicle_id)
            ->orderByDesc('date')
            ->count();

        if ($last_service_oil) {
            $oil_months = $this->params->get_app_parameters("{$last_service_oil->type_oil}_oil_months");
            $type_lifespan = $this->params->get_app_parameters("{$last_service_oil->type_oil}_oil_lifespan");
            $lifespan = $last_service_oil->life_span === 0 ? $type_lifespan : $last_service_oil->life_span;

            $percentage_km_traveled = round((($this->odometer - $last_service_oil->odometer) / (int) $lifespan) * 100, 0);
            $good_color = "#46CA31";
            $bad_color = "CC0D0D";

            $response_oil = [];
            $response_oil['oil_label'] = 'Aceite';

            $response_oil['kms_current'] = (int) $last_service_oil->odometer;
            $response_oil['date_current'] = $last_service_oil->date;
            $response_oil['kms_next'] = (int) $last_service_oil->odometer + (int) $last_service_oil->life_span;
            $response_oil['date_next'] = optional(Carbon::create($last_service_oil->date))->addMonths($oil_months)->format('Y-m-d');
            $response_oil['percentage_km_traveled'] = $percentage_km_traveled;
            $response_oil['percentage_km_traveled_color'] = ($percentage_km_traveled < 75) ? $good_color : $bad_color;
            $response_oil['percentaje_kms_good_max'] = 75;
            $response_oil['percentage_date_good_max'] = 75;
            $response_oil['percentage_date_good_color'] = $good_color;
            $response_oil['percentage_date_bad_color'] = $bad_color;
            $response_oil['no_data_kms'] = false;
        } else {
            $response_oil['no_data_kms'] = true;
        }

        if ($cant_service_oil < 2) {
            $response_oil['no_data_dates'] = true;
        } else {
            $response_oil['no_data_dates'] = false;
        }

        return $response_oil;
    }

    public function getRotationStats($vehicle_id, $last_service)
    {
        $increment_km_base = 5000;
        $no_data_dates = false;
        $no_data_kms = false;

        $last_rotation_service = Service::where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->where(function ($query) {
                $query->where('rotation_x', true)
                    ->orWhere('rotation_lineal', true);
            })
            ->orderByDesc('date')
            ->first();

        $last_service = Service::where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->orderBy('date', 'desc')
            ->first();

        $total_services = Service::where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->orderByDesc('date')
            ->count();

        if ($last_rotation_service == null) {
            $no_data_kms = true;
        }

        if ($last_rotation_service == null) {
            $no_data_dates = true;
        }

        if ($last_rotation_service != null and $total_services < 2) {
            $no_data_dates = true;
        }

        if ($last_rotation_service) {
            $kms_next = $last_rotation_service->odometer + $increment_km_base;

            $percentage_km_traveled = round(($last_service->odometer - $last_rotation_service->odometer) / $increment_km_base * 100);

            return [
                'rotation_label' => 'Rotation',
                'kms_current' => (int) $last_rotation_service->odometer,
                'date_current' => $last_rotation_service->date,
                'kms_next' => $kms_next,
                'date_next' => optional(Carbon::create($last_rotation_service->date))->addMonths(6)->format('Y-m-d'),
                'percentage_km_traveled' => $percentage_km_traveled,
                'percentage_km_traveled_color' => ($percentage_km_traveled < 75) ? '#46CA31' : '#CC0D0D',
                'percentage_kms_good_max' => 75,
                'percentage_date_good_max' => 75,
                'percentage_date_good_color' => '#46CA31',
                'percentage_date_bad_color' => '#CC0D0D',
                'no_data_kms' => $no_data_kms,
                'no_data_dates' => $no_data_dates,
            ];
        }

        return [
            'no_data_kms' => $no_data_kms,
            'no_data_dates' => $no_data_dates
        ];
    }

    public function get_rotation_details(int $vehicle_id)
    {
        $last_rotation_service = Service::where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->where(function ($query) {
                $query->where('rotation_x', 1)
                    ->orWhere('rotation_lineal', 1);
            })->orderByDesc('date')->first();


        if ($last_rotation_service) {
            return $this->calculateRotationData($last_rotation_service);
        }

        return [
            'rotation_data' => []
        ];
    }

    private function calculateRotationData($service)
    {

        $tires = ServiceTire::where('service_id', $service->odoo_id)
            ->where('location', '<>', 'Repuesto')->get();


        if ($service->rotation_lineal) {
            $rotation_response = [
                'show_arrows_left'  => true,
                'show_arrows_rigth' => true,
                'show_arrows_cross' => false,
            ];

            $response = $this->getRotationPrev($tires, $this->rotation_lineal, false);
        }

        if ($service->rotation_x) {

            $rotation_response = [
                'show_arrows_left'  => false,
                'show_arrows_rigth' => false,
                'show_arrows_cross' => true,
            ];

            $response = $this->getRotationPrev($tires, $this->rotation_x, false);
        }
        return [
            'rotation_data' => [
                'show_arrows_left'  => $rotation_response['show_arrows_left'],
                'show_arrows_rigth' => $rotation_response['show_arrows_rigth'],
                'show_arrows_cross' => $rotation_response['show_arrows_cross'],
                'tires' => $response
            ]
        ];
    }

    private function getRotationPrev($tires, $rotation_mode, $tires_details = false, $rotation_response = false, $rotation_simple = false)
    {

        $response = [];
        for ($i = 0; $i < count($tires); $i++) {
            switch ($tires[$i]['location']) {
                case 'Delantero Izquierdo':
                    $response[$i]['tire_location'] = $tires[$i]['location'];
                    $response[$i]['tire_location_capitals'] = $this->getCapitals($tires[$i]['location']);
                    $response[$i]['prev_position'] = $rotation_mode != false ? $rotation_mode[$tires[$i]['location']] : null;
                    $response[$i]['prev_position_capitals'] = $rotation_mode != false ? $this->getCapitals($rotation_mode[$tires[$i]['location']]) : null;
                    $response[$i]['tire_size'] = $tires[$i]->tire_sizes->name;
                    $response[$i]['tire_brand_name'] = $tires[$i]->tire_brands->name;
                    $response[$i]['tire_model_name'] = $tires[$i]->tire_models->name;
                    $response[$i]['service_id'] = $tires[$i]['regular'];
                    $response[$i]['regular'] = $tires[$i]['regular'];
                    $response[$i]['staggered'] = $tires[$i]['staggered'];
                    $response[$i]['central'] = $tires[$i]['central'];
                    $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                    $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                    $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                    $response[$i]['bulge'] = $tires[$i]['bulge'];
                    $response[$i]['perforations'] = $tires[$i]['perforations'];
                    $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                    $response[$i]['aging'] = $tires[$i]['aging'];
                    $response[$i]['crack'] = $tires[$i]['cracked'];
                    $response[$i]['deformations'] = $tires[$i]['deformations'];
                    $response[$i]['separations'] = $tires[$i]['separations'];

                    if ($tires_details && $rotation_simple) {
                        $response[$i]['service_id'] = $tires[$i]['regular'];
                        $response[$i]['regular'] = $tires[$i]['regular'];
                        $response[$i]['staggered'] = $tires[$i]['staggered'];
                        $response[$i]['central'] = $tires[$i]['central'];
                        $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                        $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                        $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                        $response[$i]['bulge'] = $tires[$i]['bulge'];
                        $response[$i]['perforations'] = $tires[$i]['perforations'];
                        $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                        $response[$i]['aging'] = $tires[$i]['aging'];
                        $response[$i]['crack'] = $tires[$i]['cracked'];
                        $response[$i]['deformations'] = $tires[$i]['deformations'];
                        $response[$i]['separations'] = $tires[$i]['separations'];
                    }

                    if ($rotation_response) {
                        $response[$i]['show_arrows_left'] = $rotation_response['show_arrows_left'];
                        $response[$i]['show_arrows_rigth'] = $rotation_response['show_arrows_rigth'];
                        $response[$i]['show_arrows_cross'] = $rotation_response['show_arrows_cross'];
                    }

                    break;
                case 'Trasero Izquierdo':
                    $response[$i]['tire_location'] = $tires[$i]['location'];
                    $response[$i]['tire_location_capitals'] = $this->getCapitals($tires[$i]['location']);
                    $response[$i]['prev_position'] = $rotation_mode != false ? $rotation_mode[$tires[$i]['location']] : null;
                    $response[$i]['prev_position_capitals'] = $rotation_mode != false ? $this->getCapitals($rotation_mode[$tires[$i]['location']]) : null;
                    $response[$i]['tire_size'] = $tires[$i]->tire_sizes->name;
                    $response[$i]['tire_brand_name'] = $tires[$i]->tire_brands->name;
                    $response[$i]['tire_model_name'] = $tires[$i]->tire_models->name;
                    $response[$i]['service_id'] = $tires[$i]['service_id'];
                    $response[$i]['regular'] = $tires[$i]['regular'];
                    $response[$i]['staggered'] = $tires[$i]['staggered'];
                    $response[$i]['central'] = $tires[$i]['central'];
                    $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                    $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                    $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                    $response[$i]['bulge'] = $tires[$i]['bulge'];
                    $response[$i]['perforations'] = $tires[$i]['perforations'];
                    $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                    $response[$i]['aging'] = $tires[$i]['aging'];
                    $response[$i]['crack'] = $tires[$i]['cracked'];
                    $response[$i]['deformations'] = $tires[$i]['deformations'];
                    $response[$i]['separations'] = $tires[$i]['separations'];

                    if ($tires_details && $rotation_simple) {
                        $response[$i]['service_id'] = $tires[$i]['service_id'];
                        $response[$i]['regular'] = $tires[$i]['regular'];
                        $response[$i]['staggered'] = $tires[$i]['staggered'];
                        $response[$i]['central'] = $tires[$i]['central'];
                        $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                        $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                        $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                        $response[$i]['bulge'] = $tires[$i]['bulge'];
                        $response[$i]['perforations'] = $tires[$i]['perforations'];
                        $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                        $response[$i]['aging'] = $tires[$i]['aging'];
                        $response[$i]['crack'] = $tires[$i]['cracked'];
                        $response[$i]['deformations'] = $tires[$i]['deformations'];
                        $response[$i]['separations'] = $tires[$i]['separations'];
                    }

                    if ($rotation_response) {
                        $response[$i]['show_arrows_left'] = $rotation_response['show_arrows_left'];
                        $response[$i]['show_arrows_rigth'] = $rotation_response['show_arrows_rigth'];
                        $response[$i]['show_arrows_cross'] = $rotation_response['show_arrows_cross'];
                    }

                    break;
                case 'Delantero Derecho':
                    $response[$i]['tire_location'] = $tires[$i]['location'];
                    $response[$i]['tire_location_capitals'] = $this->getCapitals($tires[$i]['location']);
                    $response[$i]['prev_position'] = $rotation_mode != false ? $rotation_mode[$tires[$i]['location']] : null;
                    $response[$i]['prev_position_capitals'] = $rotation_mode != false ? $this->getCapitals($rotation_mode[$tires[$i]['location']]) : null;
                    $response[$i]['tire_size'] = $tires[$i]->tire_sizes->name;
                    $response[$i]['tire_brand_name'] = $tires[$i]->tire_brands->name;
                    $response[$i]['tire_model_name'] = $tires[$i]->tire_models->name;
                    $response[$i]['service_id'] = $tires[$i]['service_id'];
                    $response[$i]['regular'] = $tires[$i]['regular'];
                    $response[$i]['staggered'] = $tires[$i]['staggered'];
                    $response[$i]['central'] = $tires[$i]['central'];
                    $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                    $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                    $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                    $response[$i]['bulge'] = $tires[$i]['bulge'];
                    $response[$i]['perforations'] = $tires[$i]['perforations'];
                    $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                    $response[$i]['aging'] = $tires[$i]['aging'];
                    $response[$i]['crack'] = $tires[$i]['cracked'];
                    $response[$i]['deformations'] = $tires[$i]['deformations'];
                    $response[$i]['separations'] = $tires[$i]['separations'];

                    if ($tires_details && $rotation_simple) {
                        $response[$i]['service_id'] = $tires[$i]['service_id'];
                        $response[$i]['regular'] = $tires[$i]['regular'];
                        $response[$i]['staggered'] = $tires[$i]['staggered'];
                        $response[$i]['central'] = $tires[$i]['central'];
                        $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                        $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                        $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                        $response[$i]['bulge'] = $tires[$i]['bulge'];
                        $response[$i]['perforations'] = $tires[$i]['perforations'];
                        $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                        $response[$i]['aging'] = $tires[$i]['aging'];
                        $response[$i]['crack'] = $tires[$i]['cracked'];
                        $response[$i]['deformations'] = $tires[$i]['deformations'];
                        $response[$i]['separations'] = $tires[$i]['separations'];
                    }

                    if ($rotation_response) {
                        $response[$i]['show_arrows_left'] = $rotation_response['show_arrows_left'];
                        $response[$i]['show_arrows_rigth'] = $rotation_response['show_arrows_rigth'];
                        $response[$i]['show_arrows_cross'] = $rotation_response['show_arrows_cross'];
                    }

                    break;
                case 'Trasero Derecho':
                    $response[$i]['tire_location'] = $tires[$i]['location'];
                    $response[$i]['tire_location_capitals'] = $this->getCapitals($tires[$i]['location']);
                    $response[$i]['prev_position'] = $rotation_mode != false ? $rotation_mode[$tires[$i]['location']] : null;
                    $response[$i]['prev_position_capitals'] = $rotation_mode != false ? $this->getCapitals($rotation_mode[$tires[$i]['location']]) : null;
                    $response[$i]['tire_size'] = $tires[$i]->tire_sizes->name;
                    $response[$i]['tire_brand_name'] = $tires[$i]->tire_brands->name;
                    $response[$i]['tire_model_name'] = $tires[$i]->tire_models->name;
                    $response[$i]['service_id'] = $tires[$i]['service_id'];
                    $response[$i]['regular'] = $tires[$i]['regular'];
                    $response[$i]['staggered'] = $tires[$i]['staggered'];
                    $response[$i]['central'] = $tires[$i]['central'];
                    $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                    $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                    $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                    $response[$i]['bulge'] = $tires[$i]['bulge'];
                    $response[$i]['perforations'] = $tires[$i]['perforations'];
                    $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                    $response[$i]['aging'] = $tires[$i]['aging'];
                    $response[$i]['crack'] = $tires[$i]['cracked'];
                    $response[$i]['deformations'] = $tires[$i]['deformations'];
                    $response[$i]['separations'] = $tires[$i]['separations'];

                    if ($tires_details && $rotation_simple) {
                        $response[$i]['service_id'] = $tires[$i]['service_id'];
                        $response[$i]['regular'] = $tires[$i]['regular'];
                        $response[$i]['staggered'] = $tires[$i]['staggered'];
                        $response[$i]['central'] = $tires[$i]['central'];
                        $response[$i]['right_shoulder'] = $tires[$i]['right_shoulder'];
                        $response[$i]['left_shoulder'] = $tires[$i]['left_shoulder'];
                        $response[$i]['not_apply'] = $tires[$i]['not_apply'];
                        $response[$i]['bulge'] = $tires[$i]['bulge'];
                        $response[$i]['perforations'] = $tires[$i]['perforations'];
                        $response[$i]['vulcanized'] = $tires[$i]['vulcanized'];
                        $response[$i]['aging'] = $tires[$i]['aging'];
                        $response[$i]['crack'] = $tires[$i]['cracked'];
                        $response[$i]['deformations'] = $tires[$i]['deformations'];
                        $response[$i]['separations'] = $tires[$i]['separations'];
                    }

                    if ($rotation_response) {
                        $response[$i]['show_arrows_left'] = $rotation_response['show_arrows_left'];
                        $response[$i]['show_arrows_rigth'] = $rotation_response['show_arrows_rigth'];
                        $response[$i]['show_arrows_cross'] = $rotation_response['show_arrows_cross'];
                    }

                    break;
            }
        }
        return $response;
    }

    private function getCapitals($string)
    {
        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= mb_substr($w, 0, 1);
        }

        return $acronym;
    }


    public function getVehicleTireChar($vehicle_id)
    {
        return [
            'tires_data' => $this->get_tire_details($vehicle_id)
        ];
    }

    private function get_tire_details($vehicle_id)
    {
        $last_service = Service::with('serviceTires')
            ->where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->whereHas('serviceTires')
            ->orderByDesc('date')->first();

        if (!$last_service) {
            return [];
        }

        $tires = ServiceTire::where('service_id', $last_service->odoo_id)
            ->where('location', '<>', 'Repuesto')
            ->orderBy('location')->get();

        if ($last_service->rotation_lineal) {
            $tire_details = $this->getRotationPrev($tires, $this->rotation_lineal, true, $this->rotation_response_lineal);
        } elseif ($last_service->rotation_x) {
            $tire_details = $this->getRotationPrev($tires, $this->rotation_x, true, $this->rotation_response_x);
        } else {
            $tire_details = [
                'simple' => $this->getRotationPrev($tires, false, true, $this->rotation_response_x, True),
                'no_rotation_data' => false
            ];
        }

        $tire_chart = $this->getVehicleTireChart($vehicle_id);

        $response = $this->map_tires(
            $tire_chart,
            $tire_details,
            $last_service,
            $vehicle_id,
            $tires
        );
        return $response;
    }


    private function map_tires($tire_chart, $tire_details, $last_service, $vehicle_id, $tires)
    {
        $show_alert_change_tire = $this->params->get_app_parameters('show_alert_change_tire');

        $min_alert = $this->params->get_app_parameters('min_show_change_alert_message');
        $max_alert = $this->params->get_app_parameters('max_show_change_alert_message');

        $warning_color = $this->params->get_app_parameters('warning_color');
        $danger_color = $this->params->get_app_parameters('danger_color');
        $data = [];
        for ($i = 0; $i < count($tires); $i++) {
            if (!isset($tire_details['no_rotation_data'])) {
                if (isset($tire_details[$i])) {
                    $data[$i]['show_arrows_left']  = $tire_details[$i]['show_arrows_left'];
                    $data[$i]['show_arrows_rigth'] = $tire_details[$i]['show_arrows_rigth'];
                    $data[$i]['show_arrows_cross'] = $tire_details[$i]['show_arrows_cross'];
                    $data[$i]['tire_location'] = $tire_details[$i]['tire_location'];
                    $data[$i]['tire_location_capitals'] = $this->getCapitals($tire_details[$i]['tire_location']);
                    $data[$i]['prev_position'] = $tire_details[$i]['prev_position'];
                    $data[$i]['prev_position_capitals'] = $this->getCapitals($tire_details[$i]['prev_position']);
                    $data[$i]['tire_size'] = $tire_details[$i]['tire_size'];
                    $data[$i]['tire_brand_name'] = $tire_details[$i]['tire_brand_name'];
                    $data[$i]['tire_model_name'] = $tire_details[$i]['tire_model_name'];
                    $data[$i]['regular'] = $tire_details[$i]['regular'];
                    $data[$i]['staggered'] = $tire_details[$i]['staggered'];
                    $data[$i]['central'] = $tire_details[$i]['central'];
                    $data[$i]['right_shoulder'] = $tire_details[$i]['right_shoulder'];
                    $data[$i]['left_shoulder'] = $tire_details[$i]['left_shoulder'];
                    $data[$i]['not_apply'] = $tire_details[$i]['not_apply'];
                    $data[$i]['bulge'] = $tire_details[$i]['bulge'];
                    $data[$i]['perforations'] = $tire_details[$i]['perforations'];
                    $data[$i]['vulcanized'] = $tire_details[$i]['vulcanized'];
                    $data[$i]['aging'] = $tire_details[$i]['aging'];
                    $data[$i]['crack'] = $tire_details[$i]['crack'];
                    $data[$i]['deformations'] = $tire_details[$i]['deformations'];
                    $data[$i]['separations'] = $tire_details[$i]['separations'];
                }
            } else {
                if (isset($tire_details['simple'][$i])) {
                    $data[$i]['tire_location'] = $tire_details['simple'][$i]['tire_location'];
                    $data[$i]['tire_size'] = $tire_details['simple'][$i]['tire_size'];
                    $data[$i]['tire_brand_name'] = $tire_details['simple'][$i]['tire_brand_name'];
                    $data[$i]['tire_model_name'] = $tire_details['simple'][$i]['tire_model_name'];
                    $data[$i]['regular'] = $tire_details['simple'][$i]['regular'];
                    $data[$i]['staggered'] = $tire_details['simple'][$i]['staggered'];
                    $data[$i]['central'] = $tire_details['simple'][$i]['central'];
                    $data[$i]['right_shoulder'] = $tire_details['simple'][$i]['right_shoulder'];
                    $data[$i]['left_shoulder'] = $tire_details['simple'][$i]['left_shoulder'];
                    $data[$i]['not_apply'] = $tire_details['simple'][$i]['not_apply'];
                    $data[$i]['bulge'] = $tire_details['simple'][$i]['bulge'];
                    $data[$i]['perforations'] = $tire_details['simple'][$i]['perforations'];
                    $data[$i]['vulcanized'] = $tire_details['simple'][$i]['vulcanized'];
                    $data[$i]['aging'] = $tire_details['simple'][$i]['aging'];
                    $data[$i]['crack'] = $tire_details['simple'][$i]['crack'];
                    $data[$i]['deformations'] = $tire_details['simple'][$i]['deformations'];
                    $data[$i]['separations'] = $tire_details['simple'][$i]['separations'];
                    $data[$i]['tire_location_capitals'] = $this->getCapitals($tire_details['simple'][$i]['tire_location']);
                    $data[$i]['no_rotation_data'] = true;
                }
            }
            if (!empty($tire_chart)) {
                if (isset($tire_chart[$i])) {
                    if ($tire_chart[$i]['km_proyected'] > 0 && $tire_chart[$i]['km_traveled_acum'] > 0) {
                        $percentage_km_proyected = round(($tire_chart[$i]['km_traveled_acum'] / $tire_chart[$i]['km_proyected'] * 100), 0);
                    } else {
                        $percentage_km_proyected = 0;
                    }

                    $lifespan_consumed = round($tire_chart[$i]['lifespan_consumed'], 0);

                    $performance_index = $this->get_performance_index($vehicle_id, $tire_chart[$i]['tire_location']);
                    $data[$i]["km_proyected"] = $tire_chart[$i]['km_proyected'];
                    $data[$i]["km_traveled_acum"] = $tire_chart[$i]['km_traveled_acum'];
                    $data[$i]["percentage_km_proyected"] = $percentage_km_proyected;
                    $data[$i]["lifespan_consumed"] = $lifespan_consumed;
                    $data[$i]["show_alert_change_tire"] = $lifespan_consumed > $show_alert_change_tire;
                    $data[$i]["alert_change_tire_color"] = ($lifespan_consumed >= $min_alert && $lifespan_consumed < $max_alert) ? $warning_color :
                        (($lifespan_consumed >= $max_alert) ? $danger_color : null);
                    $data[$i]["data_description_lifespan_consumed"] = $tire_chart[$i]['data_description_lifespan_consumed'];
                    $data[$i]["lifespan_consumed_initial_gradient"] = $tire_chart[$i]['lifespan_consumed_initial_gradient'];
                    $data[$i]["lifespan_consumed_final_gradient"] = $tire_chart[$i]['lifespan_consumed_final_gradient'];
                    $data[$i]["lifespan_consumed_final_background"] = $tire_chart[$i]['lifespan_consumed_final_background'];
                    $data[$i]['last_visit_date'] = $last_service->date;
                    $data[$i]["no_data_charts"] = empty($performance_index) ? true : false;
                    $data[$i]['performance_index'] = $performance_index;
                }
            }
        }
        return $data;
    }

    private function get_performance_index($vehicle_id, $tire_location)
    {
        $min_service_tires_histories = $this->params->get_app_parameters('min_service_tires_histories');

        $vehicle_tire_histories = VehicleTireHistory::where('vehicle_id', $vehicle_id)
            ->where('tire_location', '<>', 'Repuesto')
            ->where('tire_location', $tire_location)
            ->get();

        if ($vehicle_tire_histories->count() <= $min_service_tires_histories) {
            return [];
        }
    
        $sorted_histories = $vehicle_tire_histories->sortBy('service_date');
    
        return $sorted_histories->values()->map(function ($history, $index) {
            return [
                'index' => $index + 1,
                'visit_date' => $history->service_date,
                'performance_index' => $history->performance_index,
                'km_proyected' => $history->km_proyected,
            ];
        })->toArray();
    }

    public function get_alignment_details(int $vehicle_id)
    {
        $last_alignment = Service::whereHas('aligment', function ($query) {
            $query->where('state', 'done');
        })->where('vehicle_id', $vehicle_id)->orderBy('date', 'desc')->first();

        if (!$last_alignment) {
            return null;
        }

        $alignment_data = ServiceAligment::where('service_id', $last_alignment->odoo_id)
            ->whereIn('eje', ['Delantero', 'Trasero'])
            ->get()
            ->groupBy('eje');

        $front_axle = $alignment_data->get('Delantero', collect())->keyBy('valor');
        $rear_axle = $alignment_data->get('Trasero', collect())->keyBy('valor');

        return [
            'alignment_details_data' => [
                [
                    'final_data' => [
                        'front_toe_in_angle' => $front_axle->get('Finales')->full_convergence_d ?? "0",
                        'rear_toe_in_angle' => $rear_axle->get('Finales')->full_convergence_d ?? "0",
                        'di_camber_angle' => $front_axle->get('Finales')->camber_izq_d ?? "0",
                        'dd_camber_angle' => $front_axle->get('Finales')->camber_der_d ?? "0",
                        'ti_camber_angle' => $rear_axle->get('Finales')->camber_izq_d ?? "0",
                        'td_camber_angle' => $rear_axle->get('Finales')->camber_der_d ?? "0",
                        'di_caster_angle' => $front_axle->get('Finales')->semiconvergence_izq_d ?? "0",
                        'dd_caster_angle' => $front_axle->get('Finales')->semiconvergence_der_d ?? "0",
                        'ti_caster_angle' => $rear_axle->get('Finales')->semiconvergence_izq_d ?? "0",
                        'td_caster_angle' => $rear_axle->get('Finales')->semiconvergence_der_d ?? "0",
                    ],
                    'initial_data' => [
                        'front_toe_in_angle' => $front_axle->get('Precedentes')->full_convergence_d ?? "0",
                        'rear_toe_in_angle' => $rear_axle->get('Precedentes')->full_convergence_d ?? "0",
                        'di_camber_angle' => $front_axle->get('Precedentes')->camber_izq_d ?? "0",
                        'dd_camber_angle' => $front_axle->get('Precedentes')->camber_der_d ?? "0",
                        'ti_camber_angle' => $rear_axle->get('Precedentes')->camber_izq_d ?? "0",
                        'td_camber_angle' => $rear_axle->get('Precedentes')->camber_der_d ?? "0",
                        'di_caster_angle' => $front_axle->get('Precedentes')->semiconvergence_izq_d ?? "0",
                        'dd_caster_angle' => $front_axle->get('Precedentes')->semiconvergence_der_d ?? "0",
                        'ti_caster_angle' => $rear_axle->get('Precedentes')->semiconvergence_izq_d ?? "0",
                        'td_caster_angle' => $rear_axle->get('Precedentes')->semiconvergence_der_d ?? "0",
                    ]
                ]
            ]
        ];
    }

    public function getFluidStats(int $vehicle_id)
    {
        $last_service = Service::with('serviceFluids')
            ->where('vehicle_id', $vehicle_id)
            ->where('state', 'done')
            ->whereHas('serviceFluids')
            ->orderByDesc('date')->first();

        if (!$last_service) {
            return ['no_data' => true];
        }

        $fluid_service = InspectionFluid::where('service_id', $last_service->odoo_id)->first();

        $fluids = [
            'engine_oil' => $fluid_service->engine_oil,
            'engine_oil_color' => $this->getFluidColor($fluid_service->engine_oil),
            'engine_coolant' => $fluid_service->engine_coolant,
            'engine_coolant_color' => $this->getFluidColor($fluid_service->engine_coolant),
            'steering_oil' => $fluid_service->steering_oil,
            'steering_oil_color' => $this->getFluidColor($fluid_service->steering_oil),
            'brake_fluid' => $fluid_service->brake_fluid,
            'brake_fluid_color' => $this->getFluidColor($fluid_service->brake_fluid),
            'fuel_tank' => $fluid_service->fuel_tank,
            'fuel_tank_color' => $this->getFluidColor($fluid_service->fuel_tank),
            'cleaning_liquid' => $fluid_service->cleaning_liquid,
            'cleaning_liquid_color' => $this->getFluidColor($fluid_service->cleaning_liquid),
            'transmission_case_oil' => $fluid_service->transmission_case_oil,
            'transmission_case_oil_color' => $this->getFluidColor($fluid_service->transmission_case_oil),
            'front_diff_oil' => $fluid_service->front_diff_oil === 'no_aplica' ? null : $fluid_service->front_diff_oil,
            'front_diff_oil_color' => $this->getFluidColor($fluid_service->front_diff_oil),
            'rear_diff_oil' => $fluid_service->rear_diff_oil === 'no_aplica' ? null : $fluid_service->rear_diff_oil,
            'rear_diff_oil_color' => $this->getFluidColor($fluid_service->rear_diff_oil),
        ];

        $fluids_with_priority = array_map(function ($key, $value) {
            if (str_ends_with($key, '_color')) {
                return null;
            }
            $color_info = $this->getFluidColor($value);
            return [
                'key' => $key,
                'value' => $value,
                'color' => $color_info['color'],
                'priority' => $color_info['priority'],
            ];
        }, array_keys($fluids), $fluids);

        $fluids_with_priority = array_filter($fluids_with_priority);
        usort($fluids_with_priority, fn($a, $b) => $a['priority'] <=> $b['priority']);

        return [
            'fluids_label' => 'Revisión de Fluidos',
            'kms_current' => (int) $last_service->odometer,
            'date_current' => $last_service->date,
            'fluids' => array_map(function ($fluid) {
                return [
                    'key'       => $fluid['key'],
                    'value'     => $fluid['value'],
                    'color'     => $fluid['color'],
                    'priority'  => $fluid['priority'],
                ];
            }, $fluids_with_priority)
        ];
    }

    private function getFluidColor($value)
    {
        $color_priority = [
            '#FF3333' => 1,
            '#FFC633' => 2,
            '#46CC34' => 3,
            '#C0C0C0' => 4,
            null      => 5,
        ];

        $color_map = [
            'completo'      => '#46CC34',
            'reemplazado'   => '#46CC34',
            'buen_estado'   => '#46CC34',
            'incompleto'    => '#FFC633',
            'fuga'          => '#FF3333',
            'no_revisado'   => '#C0C0C0',
            'no_aplica'     => null,
        ];

        $color = $color_map[$value] ?? null;

        return [
            'color' => $color,
            'priority' => $color_priority[$color] ?? 5,
        ];
    }
}
