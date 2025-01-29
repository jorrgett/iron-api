<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\{
    ShowRequest,
    IndexRequest,
    StoreRequest,
    UpdateRequest,
    DestroyRequest
};
use App\Http\Resources\Category\{
    CategoryResource,
    CategoryCollection
};
use App\Repositories\Category\CategoryRepository;

class CategoryController extends Controller
{
    protected $categoryRepository;

    /**
     * CategoryController Constructor.
     *
     * @param CategoryRepository $categoryRepository
     */

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new CategoryCollection($this->categoryRepository->getAll($request->all())));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new CategoryResource($this->categoryRepository->create($request->all())), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, $id)
    {
        return response()->json(new CategoryResource($this->categoryRepository->getByField('id', $id)));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json(new CategoryResource($this->categoryRepository->UpdateById($id, $request->all())));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return response()->json($this->categoryRepository->destroy($id, $request->all()), 204);
    }
}
