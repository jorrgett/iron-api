<?php

namespace App\Http\Controllers;

use App\Repositories\TireStandar\TireStandarRepository;
use App\Http\Requests\TireStandar\{
    IndexRequest,
    StoreRequest,
    UpdateRequest,
    DestroyRequest
};

use App\Http\Resources\TireStandar\{
    TireStandarCollection,
    TireStandarResource
};

class TireStandarController extends Controller
{
    protected $tireStandarRepository;

    /**
     * Tire Standar Constructor.
     *
     * @param TireSizeRepository $tireStandarRepository
     */

    public function __construct(TireStandarRepository $tireStandarRepository)
    {
        $this->tireStandarRepository = $tireStandarRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TireStandarCollection($this->tireStandarRepository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new TireStandarResource($this->tireStandarRepository->create($request->all())), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $tire_standar = $this->tireStandarRepository->UpdateById($id, $request->all());

        if ($tire_standar['message']) {
            return response()->json($tire_standar, 400);
        }

        return response()->json(new TireStandarResource($this->tireStandarRepository->UpdateById($id, $request->all())));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, string $id)
    {
        return response()->json($this->tireStandarRepository->destroy($id), 204);
    }
}
