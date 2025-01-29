<?php

namespace App\Repositories\TireBrand;

use App\Models\TireBrand;
use App\Repositories\BaseInterface;


class TireBrandRepository implements BaseInterface
{

    protected $model;
    protected $tireBrand;

    /**
     * Tire Size Repository constructor.
     * @param Tire Size $tireBrand
     */
    public function __construct(TireBrand $tireBrand)
    {
        $this->model = $tireBrand;
    }

    /**
     * Get all paginated Tire Brands
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
     * Store a newly created tire brand in storage
     *
     * @param $data
     *
     */
    public function create(array $data) {}

    /**
     * Display the specified tire brand by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=') {}

    /**
     * Remove the specified tire brand in storage
     *
     * @param $id
     */
    public function destroy($id) {}

    /**
     * Update the specified tire brand in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data) {}

    public function getBatteryBrands($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('service_batteries')
            ->where('sequence_id', '>=', $sequence)
            ->where('odoo_id', '!=', 0)
            ->when($id, function ($query, $id) {
                $query->where('odoo_id', $id);
            })
            ->whereHas('service_batteries')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getOilBrands($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('service_oil')
            ->where('sequence_id', '>=', $sequence)
            ->where('odoo_id', '!=', 0)
            ->when($id, function ($query, $id) {
                $query->where('odoo_id', $id);
            })
            ->whereHas('service_oil')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getTireBrands($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('services')
            ->where('sequence_id', '>=', $sequence)
            ->where('odoo_id', '!=', 0)
            ->when($id, function ($query, $id) {
                $query->where('odoo_id', $id);
            })
            ->whereHas('services')
            ->orderBy('name', 'asc')
            ->get();
    }
}
