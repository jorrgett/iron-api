<?php

namespace App\Repositories\TireModel;

use App\Models\TireModel;
use App\Repositories\BaseInterface;


class TireModelRepository implements BaseInterface
{

    protected $model;
    protected $tireModel;

    /**
     * Tire Size Repository constructor.
     * @param Tire Size $tireModel
     */
    public function __construct(TireModel $tireModel)
    {
        $this->model = $tireModel;
    }

    /**
     * Get all paginated Tire Models
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $brand = !empty($data['brand_id']) ? (int)$data['brand_id'] : null;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('services')->where('sequence_id', '>=', $sequence)
            ->when($brand, function ($query, $brand) {
                $query->where('tire_brand_id', $brand);
            })
            ->whereHas('services', function ($query) {
                return $query->where('owner_id', auth()->user()->res_partner_id)
                    ->orWhere('driver_id', auth()->user()->res_partner_id);
            })->paginate($page);
    }

    /**
     * Store a newly created tire model in storage
     *
     * @param $data
     *
     */
    public function create(array $data) {}

    /**
     * Display the specified tire model by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=') {}

    /**
     * Remove the specified tire model in storage
     *
     * @param $id
     */
    public function destroy($id) {}

    /**
     * Update the specified tire model in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data) {}

    public function getBatteryModels($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;
        $brands = !empty($data['brand_id']) ? explode(',', $data['brand_id']) : [];
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('battery_models')
            ->where('sequence_id', '>=', $sequence)
            ->where('odoo_id', '!=', 0)
            ->when($id, function ($query, $id) {
                $query->where('odoo_id', $id);
            })
            ->when(!empty($brands), function ($query) use ($brands) {
                $query->whereIn('tire_brand_id', $brands);
            })
            ->whereHas('battery_models')
            ->orderBy('name', 'asc')
            ->get();
    }


    public function getTireModels($data)
    {
        $id = !empty($data['id']) ? (int)$data['id'] : null;
        $brands = !empty($data['brand_id']) ? explode(',', $data['brand_id']) : [];
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::with('services')
            ->where('sequence_id', '>=', $sequence)
            ->where('odoo_id', '!=', 0)
            ->when($id, function ($query, $id) {
                $query->where('odoo_id', $id);
            })
            ->when(!empty($brands), function ($query) use ($brands) {
                $query->whereIn('tire_brand_id', $brands);
            })
            ->whereHas('services')
            ->orderBy('name', 'asc')
            ->get();
    }
}
