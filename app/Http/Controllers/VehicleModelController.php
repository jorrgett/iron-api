<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleModel\IndexRequest;
use App\Repositories\VehicleModel\VehicleModelRepository;
use App\Http\Resources\VehicleModel\VehicleModelCollection;

class VehicleModelController extends Controller
{
    protected $vehicleModelRepository;

    /**
     * VehicleController Constructor.
     *
     * @param VehicleModelRepository $vehicleModelRepository
    */

    public function __construct(VehicleModelRepository $vehicleModelRepository)
    {
        $this->vehicleModelRepository = $vehicleModelRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new VehicleModelCollection($this->vehicleModelRepository->getAll($request->all())));
    }
}
