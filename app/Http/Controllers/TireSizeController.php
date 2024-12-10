<?php

namespace App\Http\Controllers;

use App\Http\Requests\TireSize\IndexRequest;
use App\Repositories\TireSize\TireSizeRepository;
use App\Http\Resources\TireSize\TireSizeCollection;


class TireSizeController extends Controller
{
    protected $tireSizeRepository;

    /**
     * VehicleController Constructor.
     *
     * @param TireSizeRepository $tireSizeRepository
    */

    public function __construct(TireSizeRepository $tireSizeRepository)
    {
        $this->tireSizeRepository = $tireSizeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TireSizeCollection($this->tireSizeRepository->getAll($request->all())));
    }
}
