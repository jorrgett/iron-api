<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceTire\IndexRequest;
use App\Repositories\ServiceTire\ServiceTireRepository;
use App\Http\Resources\ServiceTire\ServiceTireCollection;


class ServiceTiresController extends Controller
{
    protected $serviceTireRepository;

    /**
     * ServiceItemController Constructor.
     *
     * @param ServiceTireRepository $serviceTireRepository
    */

    public function __construct(ServiceTireRepository $serviceTireRepository)
    {
        $this->serviceTireRepository = $serviceTireRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceTireCollection($this->serviceTireRepository->getAll($request->all())));
    }
}
