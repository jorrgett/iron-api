<?php

namespace App\Repositories\Vehicle;

use App\Models\Vehicle;
use App\Helpers\CustomPaginator;
use App\Models\ServiceTimeLine;
use App\Models\VehicleAssignment;
use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\DB;

class VehicleRepository implements BaseInterface
{

    protected $model;
    protected $vehicle;
    protected $serviceTimeLine;

    /**
     * Vehicle Repository constructor.
     * @param Vehicle $vehicle
     */
    public function __construct(Vehicle $vehicle, ServiceTimeLine $serviceTimeLine)
    {
        $this->model = $vehicle;
        $this->serviceTimeLine = $serviceTimeLine;
    }

    /**
     * Get all paginated vehicles
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 50;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;
        $res_partner = !empty($data['res_partner_id']) ? (int)$data['res_partner_id'] : auth()->user()->res_partner_id;

        return $this->queryGetAll($sequence, $page, $res_partner);
    }

    public function getById($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->queryGetAll($sequence, $page, $data['res_partner_id']);
    }

    /**
     * Store a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data): Vehicle
    {
        $existingVehicle = $this->model::where('plate', $data['plate'])->first();

        if ($existingVehicle) {

            $assigned = VehicleAssignment::where('user_id', '!=', auth()->user()->id)
                ->where('vehicle_id', '=', $existingVehicle['id'])->first();

            if ($assigned) {
                auth()->user()->vehicles()->attach([$existingVehicle['id'] => ['created_at' => now()]]);
            }

            return $existingVehicle;
        }

        $vehicle = new Vehicle();

        $data['sequence_id'] = $vehicle->incrementSequence();

        $vehicle->fill($data);
        $vehicle->save();

        auth()->user()->vehicles()->attach([$vehicle->id => ['created_at' => now()]]);

        return $this->model::where('plate', $vehicle['plate'])->first();;
    }

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->get();
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id) {}

    /**
     * Update the specified user in storage
     *
     * @param string $plate
     * @param array $data
     */
    public function UpdateById($plate, array $data)
    {
        $vehicle = $this->model::with('services')
            ->whereRaw('UPPER(plate) = ?', [strtoupper($plate)])->first();

        if ($vehicle) {
            $vehicle->fill($data);
            $vehicle->sequence_id += 1;
            $vehicle->save();

            return $vehicle;
        }

        return null;
    }

    public function queryGetAll($sequence, $page, $res_partner = null)
    {
        $user_id = !empty($user_id) ? (int)$user_id : auth()->user()->user_id;
        $res_partner = !empty($res_partner) ? (int)$res_partner : auth()->user()->res_partner_id;
    
        $query = $this->model::with('services')
            ->where('sequence_id', '>=', $sequence)
            ->whereHas('services', function ($q) use ($res_partner) {
                $q->where('owner_id', $res_partner)
                    ->orWhere('driver_id', $res_partner);
            })->paginate($page);
    
        $paginator = new CustomPaginator(request());
    
        $merged = $this->model::with('users')
            ->whereHas('users', function ($q) use ($user_id) {
                $q->where('user_id', auth()->user()->id);
            })
            ->paginate($page);
    
        $result = $paginator->merge($query, $merged);
    
        $result = $this->addTimelineOrder($result);
    
        return $result;
    }
    
    private function addTimelineOrder($vehicles)
    {
        $timelineData = DB::table('services_timeline')
            ->select('vehicle_id', 'next_service_date')
            ->whereIn('vehicle_id', $vehicles->pluck('odoo_id'))
            ->orderBy('next_service_date', 'asc')
            ->get();
    
        $timelineMap = $timelineData->pluck('next_service_date', 'vehicle_id');
    
        $sortedVehicles = $vehicles->getCollection()->sortBy(function ($vehicle) use ($timelineMap) {
            return $timelineMap[$vehicle->odoo_id] ?? now()->addYears(100);
        })->values();
    
        $sortedVehicles->each(function ($vehicle, $index) {
            $vehicle->timeline_order = $index + 1;
        });

        $vehicles->setCollection($sortedVehicles);
    
        return $vehicles;
    }

    public function getServiceTimeline($data)
    {
        return $this->serviceTimeLine::where('vehicle_id', $data['vehicle_id'])
            ->with('vehicle')
            ->get();
    }
}
