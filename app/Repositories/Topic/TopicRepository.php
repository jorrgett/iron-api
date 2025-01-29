<?php

namespace App\Repositories\Topic;

use App\Models\Topic;
use App\Repositories\BaseInterface;

class TopicRepository implements BaseInterface
{
    protected $model;
    protected $topic;

    /**
     * Topic Repository Constructor.
     * @param Topic $topic
     */
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    /**
     * Get all paginated topics
     * 
     * @param $data
     */
    public function getAll($data)
    {
        return $this->model::where('is_active', true)
            ->when(!empty($data['service']), fn($query) => $query->where('service', $data['service']))
            ->when(isset($data['pct_lower'], $data['pct_upper']), fn($query) => $query->whereBetween('pct', [$data['pct_lower'], $data['pct_upper']]))
            ->when(!empty($data['physical_state']), fn($query) => $query->where('physical_state', $data['physical_state']))
            ->paginate($data['size'] ?? 10);
    }

    /**
     * Store a newly created record in storage
     * 
     * @param array $data
     * @return Topic
     */
    public function create(array $data)
    {}

    /**
     * Display the specified record by field.
     * 
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return Topic|null
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified record in storage
     * 
     * @param int $id
     * @param bool|null
     * @throws \Exception
     */
    public function destroy($id)
    {
        $topic = $this->getByField('id', $id);

        $topic->is_active = false;
        $topic->deleted_at = now();
        
        $topic->save();
        return $topic;
    }

    /**
     * Update the specified record in storage
     * 
     * @param int $id
     * @param array $data
     * @return Topic|null
     */
    public function UpdateById($id, array $data)
    {}
}