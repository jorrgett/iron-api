<?php

namespace App\Repositories\Odometer;

use App\Models\Odometer;
use App\Repositories\BaseInterface;


class OdometerRepository implements BaseInterface
{

    protected $model;
    protected $service;

    /**
     * Odometer Repository constructor.
     * @param Odometer $service
     */
    public function __construct(Odometer $service)
    {
        $this->model = $service;
    }

    /**
     * Get all paginated odometers
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::where('driver_id', auth()->user()->res_partner_id)
            ->where('sequence_id', '>=', $sequence)
            ->paginate($page);
    }

    /**
     * Odometer a newly created user in storage
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
