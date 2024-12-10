<?php

namespace App\Repositories\Store;

use App\Models\Store;
use App\Repositories\BaseInterface;


class StoreRepository implements BaseInterface
{

    protected $model;
    protected $store;

    /**
     * Store Repository constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->model = $store;
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
    
        return $this->model::with('services')
            ->where('sequence_id', '>=', $sequence)
            ->where('is_active', true)
            ->whereHas('services', function ($query) {
                return $query->where('owner_id', auth()->user()->res_partner_id)
                    ->orWhere('driver_id', auth()->user()->res_partner_id);
            })
            ->paginate($page);
    }

    /**
     * Get all stores with available services.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getServices(array $data)
    {
        $pageSize = !empty($data['size']) ? (int)$data['size'] : 10;
    
        $query = $this->model::where('is_active', true)
            ->whereHas('availableServices');
    
        if (!empty($data['store_id'])) {
            $query->where('id', $data['store_id']);
        }
    
        return $query->paginate($pageSize);
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
