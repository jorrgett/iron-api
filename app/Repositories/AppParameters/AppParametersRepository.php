<?php

namespace App\Repositories\AppParameters;

use App\Http\Resources\AppParameters\AppParametersCollection;
use App\Models\AppParameters;
use App\Repositories\BaseInterface;

class AppParametersRepository implements BaseInterface
{
    protected $model;

    /**
     * AppParameters Repository constructor.
     * @param AppParameters $appParameters
     */
    public function __construct(AppParameters $appParameters)
    {
        $this->model = $appParameters;
    }

    /**
     * Get all paginated records
     *
     * @param $data
     * @return AppParametersCollection
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        $filter = $data['search'] ?? null;

        $query = $this->model->query()
                            ->whereNull('deleted_at');

        if ($filter) {
            $query->where('key', 'like', "%{$filter}%");
        }

        $query->orderBy('id', 'asc');

        return $query->paginate($page);
    }

    /**
     * Store a newly created record in storage
     *
     * @param array $data
     * @return AppParameters
     */
    public function create(array $data): AppParameters
    {
        $privacyTermsConditions = new AppParameters();
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
     * @return AppParameters|null
     */
     public function getByField($field, $value, $operator = '=')
     {
         $result = $this->model::where($field, $operator, $value)
                               ->where('is_active', true)
                               ->first();
     
         return $result;
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
     * @return AppParameters|null
     */
    public function updateById($id, array $data)
    {
        $appParameters = $this->getByField('id', $id);

        if (!$appParameters) {
            return null;
        }

        $appParameters->fill($data);
        $appParameters->save();

        return $appParameters;
    }
}
