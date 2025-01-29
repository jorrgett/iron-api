<?php

namespace App\Repositories\ServiceBattery;

use App\Models\ServiceBattery;
use App\Repositories\BaseInterface;


class ServiceBatteryRepository implements BaseInterface
{

    protected $model;
    protected $serviceBattery;

    /**
     * ServiceItem Repository constructor.
     * @param ServiceItem $serviceBattery
     */
    public function __construct(ServiceBattery $serviceBattery)
    {
        $this->model = $serviceBattery;
    }

    /**
     * Get all paginated serviceItem items
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
     * ServiceItem a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
    }
}
