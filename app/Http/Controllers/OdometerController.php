<?php

namespace App\Http\Controllers;

use App\Http\Requests\Odometer\IndexRequest;
use App\Repositories\Odometer\OdometerRepository;
use App\Http\Resources\Odometer\OdometerCollection;

class OdometerController extends Controller
{
    protected $odometerRepository;

    /**
     * OdometerController Constructor.
     *
     * @param OdometerRepository $odometerRepository
    */

    public function __construct(OdometerRepository $odometerRepository)
    {
        $this->odometerRepository = $odometerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new OdometerCollection($this->odometerRepository->getAll($request->all())));
    }
}
