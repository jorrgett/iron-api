<?php

namespace App\Repositories\Vehicle;

use App\Models\Vehicle;
use App\Helpers\CustomPaginator;
use App\Models\ServiceTimeLine;
use App\Models\VehicleAssignment;
use App\Models\VehicleModelPhoto;
use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\DB;

class VehicleRepository implements BaseInterface
{

    protected $model;
    protected $vehicle;
    protected $serviceTimeLine;
    protected $model_photo;

    /**
     * Vehicle Repository constructor.
     * @param Vehicle $vehicle
     */
    public function __construct(Vehicle $vehicle, ServiceTimeLine $serviceTimeLine, VehicleModelPhoto $vehicleModelPhoto)
    {
        $this->model = $vehicle;
        $this->serviceTimeLine = $serviceTimeLine;
        $this->model_photo = $vehicleModelPhoto;
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
        $res_partner = !empty($data['res_partner_id']) ? (int)$data['res_partner_id'] : auth()->user()->contacts->pluck('odoo_id');

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

            $model = $this->model_photo->where('brand_id', $existingVehicle->vehicle_brand_id)
                ->where('model_id', $existingVehicle->vehicle_model_id)
                ->where('year', $existingVehicle->year)
                ->where('color', $existingVehicle->color)
                ->where('is_active', true)
                ->first();

            if ($model) {
                $existingVehicle->vehicle_image = $model->photo_url;
            }

            return $existingVehicle;
        }

        $vehicle = new Vehicle();

        $data['sequence_id'] = $vehicle->incrementSequence();

        $vehicle->fill($data);
        $vehicle->save();

        $model = $this->model_photo->where('brand_id', $vehicle->vehicle_brand_id)
            ->where('model_id', $vehicle->vehicle_model_id)
            ->where('year', $vehicle->year)
            ->where('color', $vehicle->color)
            ->where('is_active', true)
            ->first();

        if ($model) {
            $vehicle->vehicle_image = $model->photo_url;
        }

        auth()->user()->vehicles()->attach([$vehicle->id => ['created_at' => now()]]);

        return $vehicle;
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
        $res_partner = !empty($res_partner) ? $res_partner : auth()->user()->contacts->pluck('odoo_id');

        $query = $this->model::with('services')
            ->where('sequence_id', '>=', $sequence)
            ->whereHas('services', function ($q) use ($res_partner) {
                $q->whereIn('owner_id', $res_partner)
                    ->orWhereIn('driver_id', $res_partner);
            })->paginate($page);


        foreach ($query as $vehicle) {
            $model = $this->model_photo->where('brand_id', $vehicle->vehicle_brand_id)
                ->where('model_id', $vehicle->vehicle_model_id)
                ->where('year', $vehicle->year)
                ->where('color', $vehicle->color)
                ->where('is_active', true)
                ->first();

            if ($model) {
                $vehicle->vehicle_image = $model->photo_url;
            }
        }

        $paginator = new CustomPaginator(request());

        $merged = $this->model::with('users')
            ->whereHas('users', function ($q) use ($user_id) {
                $q->where('user_id', auth()->user()->id);
            })
            ->paginate($page);

        foreach ($merged as $vehicle) {
            $model = $this->model_photo->where('brand_id', $vehicle->vehicle_brand_id)
                ->where('model_id', $vehicle->vehicle_model_id)
                ->where('year', $vehicle->year)
                ->where('color', $vehicle->color)
                ->where('is_active', true)
                ->first();

            if ($model) {
                $vehicle->vehicle_image = $model->photo_url;
            }
        }

        $combinedCollection = collect($query->items())
            ->merge($merged->items())
            ->unique('id');

        $result = new \Illuminate\Pagination\LengthAwarePaginator(
            $combinedCollection->forPage($query->currentPage(), $query->perPage()),
            $combinedCollection->count(),
            $query->perPage(),
            $query->currentPage(),
            ['path' => $query->path()]
        );

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
