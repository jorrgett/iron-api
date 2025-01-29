<?php

namespace App\Repositories\TireStandar;

use App\Models\TireOtdStandar;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseInterface;


class TireStandarRepository implements BaseInterface
{

    protected $model;
    protected $tireStandar;

    /**
     * Tire Otd Standar Repository constructor.
     * @param Tire Size $tireStandar
     */
    public function __construct(TireOtdStandar $tireStandar)
    {
        $this->model = $tireStandar;
    }

    /**
     * Get all paginated tireStandars
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $sequence = !empty($data['sequence_id']) ? (int)$data['sequence_id'] : 1;

        return $this->model::where('sequence_id', '>=', $sequence)
            ->paginate($page);
    }

    /**
     * Store a newly created tire standar in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
        $sequence_id = $this->getLastSequence('tire_otd_standars_sequence');
        $data['sequence_id'] = $sequence_id;

        return $this->model::create($data);
    }

    /**
     * Display the specified tire standar by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $value, $operator)->first();
    }

    /**
     * Remove the specified tire standar in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
        $tire_otd = $this->getByField('id', $id);
        return $tire_otd->delete();
    }

    /**
     * Update the specified tire standar in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
        $tire_otd = $this->getByField('id', $id);

        if (!$tire_otd) {
            return ['message' =>  "Whoops, we could not find a tire_otd with ID: $id"];
        }

        $sequence_id = $this->getLastSequence('tire_otd_standars_sequence');
        $data['sequence_id'] = $sequence_id;

        $tire_otd->fill($data);
        $tire_otd->save();

        return $tire_otd;
    }

    private function getLastSequence($sequence)
    {
        return (DB::select("SELECT nextval('$sequence')"))[0]->nextval;
    }
}
