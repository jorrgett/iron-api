<?php

namespace App\Repositories\Store;

use App\Models\Service;
use App\Models\Store;
use App\Repositories\BaseInterface;


class StoreRepository implements BaseInterface
{

    protected $model;
    protected $store;

    protected $service_model;
    protected $service;

    /**
     * Store Repository constructor.
     * @param Store $store
     */
    public function __construct(Store $store, Service $service)
    {
        $this->model = $store;
        $this->service_model = $service;
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
        $pageSize = $data['size'] ?? 10;

        $query = $this->model::query()
            ->where('is_active', true)
            ->when(!empty($data['store_id']), function ($q) use ($data) {
                $q->where('id', $data['store_id']);
            })
            ->whereHas('availableServices');

        $stores = $query->paginate($pageSize);

        $stores->getCollection()->each(function ($store) {
            $services = $this->service_model::query()
                ->where('store_id', $store->odoo_id)
                ->where(function ($query) {
                    $query->whereNotNull('owner_score')
                        ->orWhereNotNull('driver_score');
                })
                ->get();

            $store->store_score = $this->calculateAverageScore($services);
            $store->feedback_quantity = $this->calculateCustomerQuantity($services);
        });

        return $stores;
    }

    /**
     * Calculate the average score for a collection of services.
     *
     * @param \Illuminate\Support\Collection $services
     * @return float
     */
    private function calculateAverageScore($services)
    {
        $totalScore = $services->sum(function ($service) {
            return array_sum(array_filter([
                $service->owner_score,
                $service->driver_score
            ])) / 2;
        });

        $serviceCount = $services->count();

        return round($serviceCount > 0 ? $totalScore / $serviceCount : 0);
    }

    /**
     * Calculate the customer quantity for a collection of services.
     *
     * @param \Illuminate\Support\Collection $services
     * @return int
     */
    private function calculateCustomerQuantity($services)
    {
        return $services->reduce(function ($count, $service) {
            $ownerId = $service->owner_id;
            $driverId = $service->driver_id;
        
            if ($service->owner_score !== null && $service->driver_score !== null) {
                if ($ownerId === $driverId) {
                    return $count + 1;
                }
        
                return $count + 2;
            }
        
            if ($service->owner_score !== null || $service->driver_score !== null) {
                return $count + 1;
            }
        
            return $count;
        }, 0);
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
