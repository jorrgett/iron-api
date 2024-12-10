<?php

namespace App\Repositories\VehicleBrand;

use App\Models\VehicleBrand;
use App\Repositories\BaseInterface;


class VehicleBrandRepository implements BaseInterface
{

    protected $model;
    protected $vehicleBrand;

    /**
     * VehicleBrand Repository constructor.
     * @param VehicleBrand $vehicleBrand
     */
    public function __construct(VehicleBrand $vehicleBrand)
    {
        $this->model = $vehicleBrand;
    }

    /**
     * Get all paginated vehicle brands
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('services')->where('sequence_id', '>=', $sequence)
            ->whereHas('services', function ($query) {
                return $query->where('owner_id', auth()->user()->res_partner_id)
                    ->orWhere('driver_id', auth()->user()->res_partner_id);
            })->paginate($page);
    }

    /**
     * Retrieve all vehicle brands with their associated vehicle models
     */
    public function getBrandsWithModels()
    {
        $vehicles = $this->model::with(['models' => function ($query) {
            $query->orderBy('name', 'asc');
        }])
            ->orderBy('name', 'asc')
            ->get();

        return $vehicles;
    }

    /**
     * Store a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data) {}

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=') {}

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id) {}

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data) {}
}
