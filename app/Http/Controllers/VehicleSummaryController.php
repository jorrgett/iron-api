<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleSummary\IndexRequest;
use App\Repositories\VehicleSummary\VehicleSummaryRepository;
use App\Http\Resources\VehicleSummary\VehicleSummaryCollection;

class VehicleSummaryController extends Controller
{
    protected $vehicleSummaryRepository;

    /**
     * VehicleSummary Controller Constructor.
     *
     * @param VehicleSummaryRepository $vehicleSummaryRepository
    */

    public function __construct(VehicleSummaryRepository $vehicleSummaryRepository)
    {
        $this->vehicleSummaryRepository = $vehicleSummaryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function getSummaries(IndexRequest $request)
    {
        return response()->json(new VehicleSummaryCollection($this->vehicleSummaryRepository->getAll($request->all())));
    }
}
