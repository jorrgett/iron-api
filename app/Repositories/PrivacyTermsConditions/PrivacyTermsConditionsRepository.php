<?php

namespace App\Repositories\PrivacyTermsConditions;

use App\Models\PrivacyTermsConditions;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseInterface;

class PrivacyTermsConditionsRepository implements BaseInterface
{
    protected $model;

    /**
     * PrivacyTermsConditions Repository constructor.
     * @param PrivacyTermsConditions $privacyTermsConditions
     */
    public function __construct(PrivacyTermsConditions $privacyTermsConditions)
    {
        $this->model = $privacyTermsConditions;
    }

    /**
     * Get all paginated records
     *
     * @param $data
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $filter = $data['search'] ?? null;

        if ($filter) {
            return $this->model->where('title', 'like', "%{$filter}%")
                ->orWhere('content', 'like', "%{$filter}%")
                ->paginate($page);
        } else {
            return $this->model::paginate($page);
        }
    }

    /**
     * Store a newly created record in storage
     *
     * @param array $data
     * @return PrivacyTermsConditions
     */
    public function create(array $data): PrivacyTermsConditions
    {
        $privacyTermsConditions = new PrivacyTermsConditions();
        $privacyTermsConditions->fill($data);
        $privacyTermsConditions->save();

        return $privacyTermsConditions;
    }

    /**
     * Display the specified record by field.
     *
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return PrivacyTermsConditions|null
     */
     public function getByField($field, $value, $operator = '=')
     {
         $result = $this->model::where($field, $operator, $value)
                               ->where('is_active', true)
                               ->first();
     
         return $result;
     }

    public function getField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified record in storage
     *
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
    public function destroy($id)
    {
        $privacyTermsConditions = $this->model::findOrFail($id);

        $privacyTermsConditions->deleted_at = now();
        $privacyTermsConditions->is_active = false;

        return $privacyTermsConditions->save();
    }

    /**
     * Update the specified record in storage
     *
     * @param int $id
     * @param array $data
     * @return PrivacyTermsConditions|null
     */
    public function updateById($id, array $data)
    {
        $privacyTermsConditions = $this->getField('id', $id);

        if (!$privacyTermsConditions) {
            return null;
        }

        $privacyTermsConditions->fill($data);
        $privacyTermsConditions->save();

        return $privacyTermsConditions;
    }

    public function getLastActiveByType()
    {
        return $this->model::where('is_active', true)
            ->whereNull('deleted_at')
            ->whereIn('type', ['P', 'T', 'A'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type')
            ->map(function ($items) {
                return $items->first();
            });
    }
}
