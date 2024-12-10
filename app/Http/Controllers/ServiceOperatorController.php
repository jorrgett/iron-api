<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceOperator\IndexRequest;
use App\Repositories\ServiceOperator\ServiceOperatorRepository;
use App\Http\Resources\ServiceOperator\ServiceOperatorCollection;

class ServiceOperatorController extends Controller
{
    protected $serviceOperatorRepository;

    /**
     * ServiceItemController Constructor.
     *
     * @param ServiceOperatorRepository $serviceOperatorRepository
    */

    public function __construct(ServiceOperatorRepository $serviceOperatorRepository)
    {
        $this->serviceOperatorRepository = $serviceOperatorRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceOperatorCollection($this->serviceOperatorRepository->getAll($request->all())));
    }
}
