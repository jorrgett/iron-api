<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategory\IndexRequest;
use App\Http\Requests\ProductCategory\UpdateRequest;
use App\Http\Resources\ProductCategory\ProductCategoryResource;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Http\Resources\ProductCategory\ProductCategoryCollection;

class ProductCategoryController extends Controller
{
    protected $productCategoryRepository;

    /**
     * ProductController Constructor.
     *
     * @param ProductCategoryRepository $productCategoryRepository
     */

    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ProductCategoryCollection($this->productCategoryRepository->getAll($request->all())));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json(new ProductCategoryResource($this->productCategoryRepository->UpdateById($id, $request->all())));
    }
}
