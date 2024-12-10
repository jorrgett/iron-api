<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\IndexRequest;
use App\Repositories\Service\ServiceRepository;
use App\Http\Resources\Service\ServiceCollection;

class ServiceController extends Controller
{
    protected $serviceRepository;

    /**
     * ServiceController Constructor.
     *
     * @param ServiceRepository $serviceRepository
    */

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceCollection($this->serviceRepository->getAll($request->all())));
    }
}
