<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceItem\IndexRequest;
use App\Http\Resources\ServiceItem\ServiceItemCollection;
use App\Repositories\ServiceItem\ServiceItemRepository;

class ServiceItemController extends Controller
{

    protected $serviceItemRepository;

    /**
     * ServiceItemController Constructor.
     *
     * @param ServiceItemRepository $serviceItemRepository
     */

    public function __construct(ServiceItemRepository $serviceItemRepository)
    {
        $this->serviceItemRepository = $serviceItemRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceItemCollection($this->serviceItemRepository->getAll($request->all())));
    }
}
