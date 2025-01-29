<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceBalancing\IndexRequest;
use App\Repositories\ServiceBalancing\ServiceBalancingRepository;
use App\Http\Resources\ServiceBalancing\ServiceBalancingCollection;

class ServiceBalancingController extends Controller
{
    protected $serviceBalancingRepository;

    /**
     * ServiceBatteryController Constructor.
     *
     * @param ServiceBalancingRepository $$serviceBalancingRepository
     */

    public function __construct(ServiceBalancingRepository $serviceBalancingRepository)
    {
        $this->serviceBalancingRepository = $serviceBalancingRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceBalancingCollection($this->serviceBalancingRepository->getAll($request->all())));
    }
}
