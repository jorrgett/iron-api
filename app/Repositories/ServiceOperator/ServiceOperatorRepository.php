<?php

namespace App\Repositories\ServiceOperator;

use App\Models\ServiceOperator;
use App\Repositories\BaseInterface;


class ServiceOperatorRepository implements BaseInterface
{

    protected $model;
    protected $serviceOperator;

    /**
     * ServiceOperator Repository constructor.
     * @param ServiceOperator $serviceOperator
     */
    public function __construct(ServiceOperator $serviceOperator)
    {
        $this->model = $serviceOperator;
    }

    /**
     * Get all paginated service operator items
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
     * Create newly created service operator in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified service operator by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified service operator in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified service operator in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
    }
}
