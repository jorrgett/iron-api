<?php

namespace App\Repositories\ProductCategory;

use App\Models\ProductCategory;
use App\Repositories\BaseInterface;


class ProductCategoryRepository implements BaseInterface
{

    protected $model;
    protected $productCategory;

    /**
     * ProductCategory Category Repository constructor.
     * @param ProductCategory $productCategory
     */
    public function __construct(ProductCategory $productCategory)
    {
        $this->model = $productCategory;
    }

    /**
     * Get all paginated product categories
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;
        return $this->model::filter()->paginate($page);
    }

    /**
     * ProductCategory a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
    }

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
    }

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
        $product_category = $this->model::where('id', $id)->first();

        if (!is_null($product_category)) {
            $product_category['category_id'] = $data['category_id'];
            $product_category['sequence_id'] = $product_category['sequence_id'] + 1;
            $product_category->save();
            return $product_category;
        }
    }
}
