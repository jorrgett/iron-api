<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ServiceOil\IndexRequest;
use App\Repositories\ServiceOil\ServiceOilRepository;
use App\Http\Resources\ServiceOil\ServiceOilCollection;

class ServiceOilController extends Controller
{
    protected $serviceOilRepository;

    /**
     * ServiceItemController Constructor.
     *
     * @param ServiceOilRepository $serviceOilRepository
     */

    public function __construct(ServiceOilRepository $serviceOilRepository)
    {
        $this->serviceOilRepository = $serviceOilRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceOilCollection($this->serviceOilRepository->getAll($request->all())));
    }
}
