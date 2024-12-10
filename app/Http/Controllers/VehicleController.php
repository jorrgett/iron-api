<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\ByResPartnerRequest;
use App\Http\Requests\Vehicle\IndexRequest;
use App\Http\Requests\Vehicle\StoreRequest;
use App\Http\Requests\Vehicle\UpdateRequest;
use App\Http\Resources\Vehicle\ServiceTimeLineResource;
use App\Repositories\Vehicle\VehicleRepository;
use App\Http\Resources\Vehicle\VehicleCollection;
use App\Http\Resources\Vehicle\VehicleResource;

class VehicleController extends Controller
{

    protected $vehicleRepository;

    /**
     * VehicleController Constructor.
     *
     * @param VehicleRepository $vehicleRepository
     */

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new VehicleCollection($this->vehicleRepository->getAll($request->all())));
    }

    /**
     * Display a listing of the resource by res partner id.
     */
    public function get_by_id(ByResPartnerRequest $request)
    {
        return response()->json(new VehicleCollection($this->vehicleRepository->getById($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $userId = auth()->id();

        $vehicleData = array_merge($request->all(), ['user_id' => $userId]);
        $vehicle = $this->vehicleRepository->create($vehicleData);

        return response()->json(new VehicleResource($vehicle), 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function update(UpdateRequest $request, string $plate)
    {
        $vehicle = $this->vehicleRepository->UpdateById($plate, $request->validated());

        if (is_null($vehicle)) {
            return response()->json(['errors' => 'You do not have permissions to execute on this vehicle'], 403);
        }

        return response()->json(new VehicleResource($vehicle));
    }

    /**
     * Display the service timeline for a determined vehicle.
     */
    public function service_timeline(IndexRequest $request)
    {
        return response()->json(
            ServiceTimeLineResource::collection($this->vehicleRepository->getServiceTimeline($request->all()))
        );
    }
}
