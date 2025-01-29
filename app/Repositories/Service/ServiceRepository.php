<?php

namespace App\Repositories\Service;

use App\Models\Service;
use App\Repositories\BaseInterface;


class ServiceRepository implements BaseInterface
{

    protected $model;
    protected $service;

    /**
     * Service Repository constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->model = $service;
    }

    /**
     * Get all paginated stores
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::where('sequence_id', '>=', $sequence)
            ->where('owner_id', auth()->user()->res_partner_id)
            ->orWhere('driver_id', auth()->user()->res_partner_id)
            ->paginate($page);
    }

    /**
     * Service a newly created user in storage
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
        return $this->model::where($field, $operator, $value)->first();
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
        $service = $this->getByField('odoo_id', $id);

        if (!$service) {
            return null;
        }

        if ($service->owner_id == $data['user_id'] && $service->owner_score == null) {
            $service->owner_score = $data['score'];
        }
        
        if ($service->driver_id == $data['user_id'] && $service->driver_score == null) {
            $service->driver_score = $data['score'];
        }

        $service->save();

        return $service;
    }
}
