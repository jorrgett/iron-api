<?php

namespace App\Http\Controllers;

use App\Http\Requests\TireModel\IndexRequest;
use App\Repositories\TireModel\TireModelRepository;
use App\Http\Resources\TireModel\TireModelCollection;
use App\Http\Resources\TireModel\TireModelResource;

class TireModelController extends Controller
{
    protected $tireModelRepository;

    /**
     * VehicleController Constructor.
     *
     * @param TireModelRepository $tireModelRepository
    */

    public function __construct(TireModelRepository $tireModelRepository)
    {
        $this->tireModelRepository = $tireModelRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TireModelCollection($this->tireModelRepository->getAll($request->all())));
    }

    /**
     * Display a listing of battery models.
     */
    public function getBatteryModels(IndexRequest $request)
    {
        return response()->json(
            TireModelResource::collection($this->tireModelRepository->getBatteryModels($request->all()))
        );
    }

    /**
     * Display a listing of tire models.
     */
    public function getTireModels(IndexRequest $request)
    {
        return response()->json(
            TireModelResource::collection($this->tireModelRepository->getTireModels($request->all()))
        );
    }
}
