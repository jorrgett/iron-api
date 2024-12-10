<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleBrand\IndexRequest;
use App\Repositories\VehicleBrand\VehicleBrandRepository;
use App\Http\Resources\VehicleBrand\VehicleBrandCollection;

class VehicleBrandController extends Controller
{
    protected $vehicleBrandRepository;

    /**
     * VehicleController Constructor.
     *
     * @param VehicleBrandRepository $vehicleBrandRepository
    */

    public function __construct(VehicleBrandRepository $vehicleBrandRepository)
    {
        $this->vehicleBrandRepository = $vehicleBrandRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new VehicleBrandCollection($this->vehicleBrandRepository->getAll($request->all())));
    }

    /**
     * Display all brands with their models.
     */
    public function getBrandsWithModels()
    {
        $brandsWithModels = $this->vehicleBrandRepository->getBrandsWithModels();
        return response()->json($brandsWithModels);
    }
}
