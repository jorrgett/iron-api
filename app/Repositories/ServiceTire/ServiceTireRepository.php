<?php

namespace App\Repositories\ServiceTire;

use App\Models\ServiceTire;
use App\Repositories\BaseInterface;


class ServiceTireRepository implements BaseInterface
{

    protected $model;
    protected $serviceTire;

    /**
     * ServiceTire Repository constructor.
     * @param ServiceTire $serviceTire
     */
    public function __construct(ServiceTire $serviceTire)
    {
        $this->model = $serviceTire;
    }

    /**
     * Get all paginated service tire items
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
     * Create newly created service tires in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified service tires by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified service tires in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified service tires in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
    }
}
