<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use App\Repositories\Product\ProductRepository;
use App\Http\Resources\Product\ProductCollection;

class ProductController extends Controller
{
    protected $productRepository;

    /**
     * ProductController Constructor.
     *
     * @param ProductRepository $productRepository
    */

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ProductCollection($this->productRepository->getAll($request->all())));
    }

}
