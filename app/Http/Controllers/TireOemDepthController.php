<?php

namespace App\Http\Controllers;

use App\Http\Requests\TireOemDepth\ShowRequest;
use App\Http\Requests\TireOemDepth\IndexRequest;
use App\Http\Requests\TireOemDepth\StoreRequest;
use App\Http\Requests\TireOemDepth\UpdateRequest;
use App\Http\Requests\TireOemDepth\DestroyRequest;
use App\Http\Resources\TireOemDepth\TireOemDepthResource;
use App\Repositories\TireOemDepth\TireOemDepthRepository;
use App\Http\Resources\TireOemDepth\TireOemDepthCollection;

class TireOemDepthController extends Controller
{

    protected $tireOemDepthRepository;

    /**
     * TireOemDepthController Constructor.
     *
     * @param TireOemDepthRepository $tireOemDepthRepository
    */

    public function __construct(TireOemDepthRepository $tireOemDepthRepository)
    {
        $this->tireOemDepthRepository = $tireOemDepthRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TireOemDepthCollection($this->tireOemDepthRepository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new TireOemDepthResource($this->tireOemDepthRepository->create($request->all())), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, string $id)
    {
        $getById = $this->tireOemDepthRepository->getByField('id', $id);
        return response()->json($getById, !empty($getById) ? 200 : 204);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        return response()->json(new TireOemDepthResource($this->tireOemDepthRepository->UpdateById($id, $request->all())));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, string $id)
    {
        return response()->json($this->tireOemDepthRepository->destroy($id), 204);
    }
}
