<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseInterface;


class CategoryRepository implements BaseInterface
{

    protected $model;
    protected $category;

    /**
     * Category Repository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * Get all paginated odometers
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;

        return $this->model::paginate($page);
    }

    /**
     * Category a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
        $category = $this->getByField('id', $id);
        return !is_null($category) ? $category->delete() : True;
    }

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
        $category = $this->getByField('id', $id);
        $category->fill($data);
        $category->save();

        return $category;
    }
}
