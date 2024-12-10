<?php

namespace App\Http\Controllers;

use App\Http\Requests\Store\IndexRequest;
use App\Repositories\Store\StoreRepository;
use App\Http\Resources\Store\StoreCollection;
use App\Http\Resources\StoreServices\StoreServicesCollection;

class StoreController extends Controller
{

    protected $storeRepository;

    /**
     * StoreController Constructor.
     *
     * @param UserRepository $storeRepository
    */

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new StoreCollection($this->storeRepository->getAll($request->all())));
    }

    public function store_services(IndexRequest $request)
    {
        return response()->json(new StoreServicesCollection($this->storeRepository->getServices($request->all())));
    }

}
