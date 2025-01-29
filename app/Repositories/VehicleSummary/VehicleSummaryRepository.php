<?php

namespace App\Repositories\VehicleSummary;

use App\Models\VehicleSummary;
use App\Repositories\BaseInterface;


class VehicleSummaryRepository implements BaseInterface
{

    protected $model;
    protected $vehicleSummary;

    /**
     * VehicleSummary Repository constructor.
     * @param VehicleSummary $vehicleSummary
     */
    public function __construct(VehicleSummary $vehicleSummary)
    {
        $this->model = $vehicleSummary;
    }

    /**
     * Get all paginated vehicle summaries
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('services')->where('sequence_id', '>=', $sequence)
            ->whereHas('vehicles', function ($query) {
                return $query->where('owner_id', auth()->user()->res_partner_id)
                    ->orWhere('driver_id', auth()->user()->res_partner_id);
            })->paginate($page);
    }

    /**
     * Store a newly created vehicle summary in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified vehicle summary by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified vehicle summary in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified vehicle summary in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
    }
}
