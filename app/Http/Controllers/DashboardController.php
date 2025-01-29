<?php

namespace App\Http\Controllers;

use App\Helpers\DashboardHelper;
use App\Http\Requests\Dashboard\DashboardBatteryRequest;
use App\Http\Requests\Dashboard\RotationDetailRequest;
use App\Http\Requests\Dashboard\TireBalancingRequest;
use App\Http\Resources\Dashboard\BatteryStatsResource;
use App\Http\Resources\Dashboard\ServiceBalancingResource;
use App\Http\Resources\Dashboard\ServiceBatteryResource;
use App\Models\Service;
use App\Models\ServiceBalancing;
use App\Models\ServiceBattery;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $helper;
    protected $users;

    /**
     * DashboardController Constructor.
     *
     * @param DashboardHelper $helper
     */

    public function __construct(DashboardHelper $helper)
    {
        $this->helper = $helper;
    }

    public function viewOilChart()
    {
        return response()->json([
            'vehicle_oil_chart' => $this->helper->get_oil_chart()
        ]);
    }

    public function viewTireChart($vehicle_id)
    {
        return response()->json([
            'vehicle_tire_chart' => $this->helper->getVehicleTireChart($vehicle_id)
        ]);
    }

    public function getDashboard()
    {
        return $this->getBatteryRecord();
    }

    protected function getBatteryRecord()
    {
        $status_batteries = DB::select(
            "SELECT * FROM count_all_batteries_status()"
        );

        $data = [];

        foreach ($status_batteries as $key => $value) {
            $data[$value->status_battery] = $value->quantity;
        }

        $custom_order = [
            "Nueva",
            "Buen estado",
            "Recargar",
            "Reemplazar",
            "Dañada",
        ];

        $ordered_data = [];
        foreach ($custom_order as $label) {
            if (isset($data[$label])) {
                $ordered_data['labels'][] = $label;
                $ordered_data['data'][] = $data[$label];
            }
        }

        return [
            'users_with_batteries' => $ordered_data,
        ];
    }


    public function AllUsersTiresLifespandConsumedStatus()
    {
        try {
            $resultados = DB::select("SELECT * FROM count_all_users_tires_lifespand_consumed_status()");

            if (empty($resultados)) {
                return response()->json(['message' => 'No se encontraron resultados.'], 404);
            }

            $totalCantidad = array_sum(array_column($resultados, 'cantidad'));

            $resultadosConPorcentajes = array_map(function ($resultado) use ($totalCantidad) {
                $resultado->users = ($totalCantidad > 0) ? round(($resultado->cantidad / $totalCantidad) * 100) : 0;
                return $resultado;
            }, $resultados);

            $collection = collect($resultadosConPorcentajes);
            $sorted = $collection->sortByDesc('status')->values()->all();

            return response()->json($sorted, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el estado del consumo de vida útil de los neumáticos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function allUsersTiresRequireChange()
    {
        try {
            $resultados = DB::select("SELECT * FROM count_all_users_tires_require_change()");

            if (empty($resultados)) {
                return response()->json(['message' => 'No se encontraron resultados.'], 404);
            }

            $totalCantidad = array_sum(array_column($resultados, 'cantidad'));

            $resultadosConPorcentajes = array_map(function ($resultado) use ($totalCantidad) {
                $resultado->users = ($totalCantidad > 0) ? round(($resultado->cantidad / $totalCantidad) * 100) : 0;
                return $resultado;
            }, $resultados);

            return response()->json($resultadosConPorcentajes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el estado del cambio de neumáticos',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getServiceBalancingStatus()
    {
        try {
            $resultados = DB::select("SELECT * FROM count_all_users_service_balancing_status()");

            if (empty($resultados)) {
                return response()->json(['message' => 'No se encontraron resultados.'], 404);
            }

            $totalUsers = array_sum(array_column($resultados, 'users'));

            $resultadosConPorcentajes = array_map(function ($resultado) use ($totalUsers) {
                $resultado->users = ($totalUsers > 0) ? round(($resultado->users / $totalUsers) * 100) : 0;
                return $resultado;
            }, $resultados);

            return response()->json($resultadosConPorcentajes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el estado de balanceo del servicio',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getServiceOilChangeStatus()
    {
        try {
            $resultados = DB::select("SELECT * FROM count_all_users_service_oil_change_status()");

            if (empty($resultados)) {
                return response()->json(['message' => 'No se encontraron resultados.'], 404);
            }

            $totalUsers = array_sum(array_column($resultados, 'users'));

            $resultadosConPorcentajes = array_map(function ($resultado) use ($totalUsers) {
                $resultado->users = ($totalUsers > 0) ? round(($resultado->users / $totalUsers) * 100) : 0;
                return $resultado;
            }, $resultados);

            return response()->json($resultadosConPorcentajes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener el estado del cambio de aceite del servicio',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getUsersActivityStatus()
    {
        try {
            $results = DB::select("
                SELECT * FROM count_all_users_activity_status()
            ");

            $months = [];
            $usersUsingApp = [];
            $registeredUsers = [];

            foreach ($results as $result) {
                $months[] = $result->months;
                $usersUsingApp[] = $result->users_using_app;
                $registeredUsers[] = $result->registered_users;
            }

            $jsonData = [
                'months' => $months,
                'users_using_app' => $usersUsingApp,
                'registered_users' => $registeredUsers
            ];

            return response()->json($jsonData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersBatteriesSummary(Request $request, $status)
    {
        try {
            switch ($status) {
                case 0:
                    $statusText = 'Usuarios con baterías registradas';
                    break;
                case 1:
                    $statusText = 'Usuarios sin baterías registradas';
                    break;
                case 2:
                    $statusText = 'Usuarios con recargas';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_batteries_summary_status(?)
            ", [$statusText]);

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false ||
                        stripos($item->battery_brand_name, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserBatteriesState(Request $request, $status)
    {
        try {
            $statusText = '';
            switch ($status) {
                case 0:
                    $statusText = 'Buen estado';
                    break;
                case 1:
                    $statusText = 'Fuga de líquido';
                    break;
                case 2:
                    $statusText = 'Bornes sulfatados y/o dañados';
                    break;
                case 3:
                    $statusText = 'Cables partidos y/o sulfatados';
                    break;
                case 4:
                    $statusText = 'Carcasa partida o impactada';
                    break;
                case 5:
                    $statusText = 'Batería Inflada';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_batteries_physical_state(?)
            ", [$statusText]);

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false ||
                        stripos($item->battery_brand_name, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function usersBatteryPhysicalState()
    {
        try {
            $results = DB::select("
                SELECT * FROM count_all_users_batteries_physical_state()
            ");

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersBatteryStatus(Request $request, $status)
    {
        try {
            switch ($status) {
                case 1:
                    $statusText = 'Buen estado';
                    break;
                case 2:
                    $statusText = 'Recargar';
                    break;
                case 3:
                    $statusText = 'Reemplazar';
                    break;
                case 4:
                    $statusText = 'Dañada';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_batteries_status(?)", [$statusText]);

            foreach ($results as $result) {
                $result->status = $statusText;
            }

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false ||
                        stripos($item->battery_brand_name, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getServiceDetails(Request $request)
    {
        $user = $request->input('user');
        $vehicle = $request->input('vehicle');
        $store = $request->input('store');

        try {
            if (is_null($user) && is_null($vehicle) && is_null($store)) {
                return response()->json(['error' => 'Al menos un parámetro debe ser proporcionado'], 400);
            }

            $results = DB::select("
                SELECT *
                FROM get_all_detail_services_status(?, ?, ?)
            ", [$user, $vehicle, $store]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countBatterySummaryStatus()
    {
        try {
            $results = DB::select("
                SELECT * FROM count_all_users_batteries_summary_status()
            ");

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countTireSummaryPhysicalState()
    {
        try {
            $results = DB::select("
                SELECT * FROM count_all_users_tires_summary_physical_state()
            ");

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByServiceBalancingStatus($status)
    {
        try {
            $statusText = '';

            switch ($status) {
                case 1:
                    $statusText = 'No Requieren Servicio';
                    break;
                case 2:
                    $statusText = 'Requieren Servicio';
                    break;
                case 3:
                    $statusText = 'Sin Servicios Registrados';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_service_balancing_status(?)
            ", [$statusText]);

            foreach ($results as $result) {
                $result->status = $statusText;
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByOilChangeStatus($status)
    {
        try {
            $statusText = '';

            switch ($status) {
                case 1:
                    $statusText = 'Requiere Cambio de Aceite';
                    break;
                case 2:
                    $statusText = 'Aceite Saludable';
                    break;
                case 3:
                    $statusText = 'Sin Cambios Registrados';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_service_oil_change_status(?)
            ", [$statusText]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserActivities(Request $request)
    {
        try {
            $activity = strtoupper($request->query('activity', ''));
            $month = $request->query('month');
    
            $results = DB::select("
                SELECT * FROM get_all_details_users_activity(?)
            ", [$activity]);
     
            if (!is_null($month)) {
                if ($month < 1 || $month > 12) {
                    return response()->json(['error' => 'Invalid Month'], 400);
                }
     
                $results = array_filter($results, function ($item) use ($month) {
                    return $item->month == $month;
                });
            }
    
            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByTiresRequireChange(Request $request, $status)
    {
        try {
            $statusText = '';

            switch ($status) {
                case 1:
                    $statusText = 'Requieren Cambio';
                    break;
                case 2:
                    $statusText = 'No Requieren Cambio';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_tires_require_change(?)
            ", [$statusText]);

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByTiresSummaryPhysicalState(Request $request, $status)
    {
        try {
            $statusText = '';

            switch ($status) {
                case 1:
                    $statusText = 'Buen estado';
                    break;
                case 2:
                    $statusText = 'Perforaciones';
                    break;
                case 3:
                    $statusText = 'Abultamiento';
                    break;
                case 4:
                    $statusText = 'Deformaciones';
                    break;
                case 5:
                    $statusText = 'Separaciones';
                    break;
                case 6:
                    $statusText = 'Grietas';
                    break;
                case 7:
                    $statusText = 'Vulcanizado';
                    break;
                case 8:
                    $statusText = 'Envejecimiento';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_tires_summary_physical_state(?)
            ", [$statusText]);

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUsersByTiresLifespanConsumedStatus(Request $request, $status)
    {
        try {
            $statusText = '';

            switch ($status) {
                case 1:
                    $statusText = '0%-25%';
                    break;
                case 2:
                    $statusText = '26%-50%';
                    break;
                case 3:
                    $statusText = '51%-75%';
                    break;
                case 4:
                    $statusText = '76%-100%';
                    break;
                default:
                    return response()->json(['error' => 'Estado no válido'], 400);
            }

            $search = $request->input('search', '');

            $results = DB::select("
                SELECT * FROM get_all_detail_users_by_tires_lifespand_consumed_status(?)
            ", [$statusText]);

            if (!empty($search)) {
                $results = array_filter($results, function ($item) use ($search) {
                    return stripos($item->full_name, $search) !== false ||
                        stripos($item->email, $search) !== false ||
                        stripos($item->ubicacion, $search) !== false ||
                        stripos($item->plate, $search) !== false;
                });
            }

            return response()->json(array_values($results));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serviceByUser(Request $request)
    {
        try {
            $user_id = $request->query('user_id');
            $service_id = $request->query('service_id');

            if (is_null($user_id)) {
                return response()->json(['error' => 'Faltan parámetros user_id'], 400);
            }

            if (is_null($service_id)) {
                $service_id = '';
            }

            $results = DB::select("
                SELECT * FROM get_all_detail_services_by_user(?, ?)
            ", [$user_id, $service_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function vehicleByUser($user_id)
    {
        try {
            $results = DB::select("
                SELECT * FROM get_all_detail_vehicles_by_user(?)
            ", [$user_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serviceTireComplete(Request $request)
    {
        try {
            $vehicle_id = $request->query('vehicle_id');
            $service_id = $request->query('service_id');

            if (is_null($vehicle_id) || is_null($service_id)) {
                return response()->json(['error' => 'Faltan parámetros vehicle_id o service_id'], 400);
            }

            $results = DB::select("
                SELECT tire_location, tire_brand_name, tire_model_name, tire_size_name, otd, tread_depth, mm_consumed, starting_pressure, finishing_pressure
                FROM services_tires_histories_complete
                WHERE vehicle_id = ? AND service_id = ?
            ", [$vehicle_id, $service_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serviceBalancingComplete(Request $request)
    {
        try {
            $vehicle_id = $request->query('vehicle_id');
            $service_id = $request->query('service_id');

            if (is_null($vehicle_id) || is_null($service_id)) {
                return response()->json(['error' => 'Faltan parámetros vehicle_id o service_id'], 400);
            }

            $results = DB::select("
                SELECT tire_location, wheel_good_state, wheel_scratched, wheel_cracked, wheel_bent, wheel_good_state_desc, wheel_scratched_desc, wheel_cracked_desc, wheel_bent_desc, lead_used
                FROM services_balancing_histories_complete
                WHERE vehicle_id = ? AND service_id = ?
            ", [$vehicle_id, $service_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serviceBatteryComplete(Request $request)
    {
        try {
            $vehicle_id = $request->query('vehicle_id');
            $service_id = $request->query('service_id');

            if (is_null($vehicle_id) || is_null($service_id)) {
                return response()->json(['error' => 'Faltan parámetros vehicle_id o service_id'], 400);
            }

            $results = DB::select("
                SELECT battery_brand_name, battery_model_name, amperage, starting_current, battery_voltage, alternator_voltage, health_percentage, health_status_final, health_status, warranty_date,
                buen_estado_desc, fuga_de_liquido_desc, bornes_sulfatados_desc, cables_partidos_desc, carcasa_partida_desc, bateria_inflada_desc, recarga_bateria_desc
                FROM services_battery_histories_complete
                WHERE vehicle_id = ? AND service_id = ?
            ", [$vehicle_id, $service_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function serviceInspections(Request $request)
    {
        try {
            $user_id = $request->query('user_id');
            $service_id = $request->query('service_id');

            if (is_null($user_id) || is_null($service_id)) {
                return response()->json(['error' => 'Faltan parámetros user_id o service_id'], 400);
            }

            $results = DB::select("
                SELECT * FROM get_service_inspections(?, ?)
            ", [$user_id, $service_id]);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchServices(Request $request)
    {
        try {
            $plate = $request->query('plate');
            $service_date = $request->query('service_date');
            $owner_name = $request->query('owner_name');
            $pageSize = $request->query('size', 10); // Si no se especifica, se usará 10 como tamaño de página predeterminado

            $query = DB::table('services_by_user_complete');

            if (!is_null($plate)) {
                $query->where('plate', 'ilike', '%' . $plate . '%');
            }
            if (!is_null($service_date)) {
                $query->whereDate('service_date', $service_date);
            }
            if (!is_null($owner_name)) {
                $query->where('owner_name', 'ilike', '%' . $owner_name . '%');
            }

            $results = $query->select([
                'user_id',
                'owner_id',
                'service_id',
                'vehicle_id',
                'vehicle_brand_name',
                'vehicle_model_name',
                'plate',
                'owner_id',
                'owner_name',
                'driver_name',
                'service_date',
                'status'
            ])->paginate($pageSize);

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTireHistories(Request $request)
    {
        try {
            $vehicle_id = $request->query('vehicle_id');
            if (is_null($vehicle_id)) {
                return response()->json(['error' => 'El parámetro vehicle_id es obligatorio'], 400);
            }

            $tire_location = $request->query('tire_location');

            $query = DB::table('services_tires_histories_complete')
                ->where('vehicle_id', $vehicle_id);

            if (!is_null($tire_location)) {
                $query->whereRaw('LOWER(tire_location) = ?', [strtolower($tire_location)]);
            }

            $query->orderBy('service_date')
                ->orderBy('tire_location');

            $results = $query->select([
                'service_id',
                'vehicle_id',
                'vehicle_brand_name',
                'vehicle_model_name',
                'service_id',
                'odometer',
                'service_date',
                'tire_brand_name',
                'tire_model_name',
                'tire_size_name',
                'tire_location',
                'tread_depth',
                'lifespan_consumed',
                'mm_consumed',
                'km_travled',
                'km_proyected',
                'otd',
                'performance_index',
                'dot',
                'starting_pressure',
                'finishing_pressure',
                'change_status',
                'traveled_percentage'
            ])->get();

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function getTireBalancing(TireBalancingRequest $request, $vehicle_id)
    {
        $check = Vehicle::where('odoo_id', $vehicle_id)->first();

        if (!$check) {
            return response()->json([], 204);
        }

        $odometer_now = (int) (DB::table('services')->select('odometer')
            ->where('vehicle_id', $vehicle_id)
            ->orderBy('date', 'desc')
            ->first())->odometer;

        $last_balancing = DB::table('services', 's')->join('service_balancing', 's.odoo_id', '=', 'service_balancing.service_id')
            ->select('s.odometer', 's.date', 'service_balancing.location')
            ->where('s.vehicle_id', $vehicle_id)
            ->where('service_balancing.balanced', true)
            ->orderBy('s.date', 'desc')
            ->first();


        if ($last_balancing != null) {
            return [
                'data' => [
                    'actual_odometer' => $odometer_now,
                    'last_balancing_km' => $last_balancing->odometer ?? null,
                    'last_balancing_date' => $last_balancing->date ?? null,
                    'next_balancing_km' => (int) $last_balancing->odometer + 8000 ?? null,
                    'next_balancing_date' => Carbon::create($last_balancing->date)->addMonths(6)->format('Y-m-d'),
                    'km_progress_bar_percent' => round(($odometer_now / ((int) $last_balancing->odometer + 8000)) * 100, 0),
                    'km_progress_bar_color' => ($odometer_now -  (int) $last_balancing->odometer) < 5000 ? '#46CA31' : '#CC0D0D'
                ]
            ];
        }

        return response()->json([
            'error' => 'Este vehiculo no cuenta con servicio de balanceo'
        ], 400);
    }

    public function getStatsBattery(DashboardBatteryRequest $request, $vehicle_id)
    {
        $check = Vehicle::where('odoo_id', $vehicle_id)->first();

        if (!$check) {
            return response()->json([], 204);
        }

        $service = Service::where('vehicle_id', $check->odoo_id)->orderBy('date', 'desc')->first();
        return response()->json(new BatteryStatsResource(ServiceBattery::where('service_id', $service->odoo_id)->first()));
    }

    public function getServiceBattery(DashboardBatteryRequest $request, $vehicle_id)
    {
        $vehicle = Vehicle::where('odoo_id', $vehicle_id)->first();

        if (!$vehicle) {
            return [
                'battery_details_data' => []
            ];
        }

        $services = Service::with('serviceBattery')
            ->where('vehicle_id', $vehicle->odoo_id)
            ->where('state', 'done')
            ->whereHas('serviceBattery')
            ->orderBy('date', 'desc')
            ->get();

        if ($services->isEmpty()) {
            return [
                'battery_details_data' => []
            ];
        }

        $dates = [];
        $currentYear = date('Y');

        foreach ($services as $service) {
            $battery = ServiceBattery::where('service_id', $service->odoo_id)
                ->where('battery_charged', true)
                ->first();
            if ($battery && date('Y', strtotime($service->date)) == $currentYear) {
                $dates[] = $service->date;
            }
        }

        $battery = ServiceBattery::where('service_id', $services->first()->odoo_id)->first();
        return response()->json(new ServiceBatteryResource($battery, $dates));
    }

    public function getServiceBalancing($vehicle_id)
    {
        try {
            $services = Service::with('serviceBalancing')
                ->where('vehicle_id', $vehicle_id)
                ->where('state', 'done')
                ->whereHas('serviceBalancing')
                ->orderByDesc('date')
                ->first();
    
            if (!$services) {
                return [];
            }
    
            $lastBalanced = ServiceBalancing::where('service_id', $services->odoo_id)->get();
    
            $locationMap = [
                'Delantero Izquierdo' => 'Front Left',
                'Delantero Derecho' => 'Front Right',
                'Trasero Derecho' => 'Rear Right',
                'Trasero Izquierdo' => 'Rear Left',
            ];
    
            $serviceBalancing = [];
    
            foreach ($lastBalanced as $index => $value) {
                if (isset($locationMap[$value->location])) {
                    $serviceBalancing[$index] = $value;
                    $serviceBalancing[$index]->lead_used = $value->lead_used;
                }
            }
    
            return response()->json(ServiceBalancingResource::collection($serviceBalancing));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor', 'message' => $e->getMessage()], 500);
        }
    }

    public function getServiceDashboard(DashboardBatteryRequest $request, $vehicle_id)
    {
        return response()->json($this->helper->get_service_chart($vehicle_id, auth()->user()->res_partner_id));
    }

    public function getRotationDetails(RotationDetailRequest $request, $vehicle_id)
    {
        return response()->json($this->helper->get_rotation_details($vehicle_id));
    }

    public function getOilChangeDetails($vehicle_id)
    {
        return response()->json($this->helper->get_oil_change_details($vehicle_id));
    }

    public function getTireDetails($vehicle_id)
    {
        return response()->json($this->helper->getVehicleTireChar($vehicle_id));
    }

    public function getAlignmentDetails($vehicle_id)
    {
        return response()->json($this->helper->get_alignment_details($vehicle_id));
    }
}
