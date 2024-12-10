<?php

namespace App\Repositories\TireOemDepth;

use App\Models\TireOemDepth;
use App\Repositories\BaseInterfaceWithSP;


class TireOemDepthRepository implements BaseInterfaceWithSP
{

    protected $model;
    protected $tireOemDepth;

    /**
     * TireOemDepth Repository constructor.
     * @param TireOemDepth $tireOemDepth
     */
    public function __construct(TireOemDepth $tireOemDepth)
    {
        $this->model = $tireOemDepth;
    }

    /**
     * Get all paginated tire oem depths
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::paginate($page);
    }

    /**
     * Display the specified tire oem depth by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified tire oem depth in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
        return $this->model::findOrFail($id)->delete();
    }

    /**
     * Store a newly or update tire depth in storage
     *
     * @param $data
     * @param $id = nullable
     *
     */
    public function createOrUpdate($data, $id = null)
    {

    }
}
