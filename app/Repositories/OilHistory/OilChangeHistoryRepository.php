<?php

namespace App\Repositories\OilHistory;

use App\Models\OilChangeHistory;
use App\Repositories\BaseInterface;


class OilChangeHistoryRepository implements BaseInterface
{

    protected $model;
    protected $oilChangeHistory;

    /**
     * OilChangeHistory Repository constructor.
     * @param OilChangeHistory $oilChangeHistory
     */
    public function __construct(OilChangeHistory $oilChangeHistory)
    {
        $this->model = $oilChangeHistory;
    }

    /**
     * Get all paginated vehicle summaries
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
     * Store a newly created vehicle summary in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified vehicle summary by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified vehicle summary in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified vehicle summary in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
    }
}
