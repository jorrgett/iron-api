<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceBattery\IndexRequest;
use App\Repositories\ServiceBattery\ServiceBatteryRepository;
use App\Http\Resources\ServiceBattery\ServiceBatteryCollection;

class ServiceBatteryController extends Controller
{
    protected $serviceBatteryRepository;

    /**
     * ServiceBatteryController Constructor.
     *
     * @param ServiceBatteryRepository $$serviceBatteryRepository
     */

    public function __construct(ServiceBatteryRepository $serviceBatteryRepository)
    {
        $this->serviceBatteryRepository = $serviceBatteryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceBatteryCollection($this->serviceBatteryRepository->getAll($request->all())));
    }
}
