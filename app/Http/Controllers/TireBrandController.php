<?php

namespace App\Http\Controllers;

use App\Http\Requests\TireBrand\IndexRequest;
use App\Repositories\TireBrand\TireBrandRepository;
use App\Http\Resources\TireBrand\TireBrandCollection;
use App\Http\Resources\TireBrand\TireBrandResource;

class TireBrandController extends Controller
{
    protected $tireBrandRepository;

    /**
     * TireBrandController Constructor.
     *
     * @param TireBrandRepository $tireBrandRepository
    */

    public function __construct(TireBrandRepository $tireBrandRepository)
    {
        $this->tireBrandRepository = $tireBrandRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TireBrandCollection($this->tireBrandRepository->getAll($request->all())));
    }

    /**
     * Display a listing of battery brands.
     */
    public function getBatteryBrands(IndexRequest $request)
    {
        return response()->json(
            TireBrandResource::collection($this->tireBrandRepository->getBatteryBrands($request->all()))
        );
    }

    /**
     * Display a listing of oil brands.
     */
    public function getOilBrands(IndexRequest $request)
    {
        return response()->json(
            TireBrandResource::collection($this->tireBrandRepository->getOilBrands($request->all()))
        );
    }

    /**
     * Display a listing of tire brands.
     */
    public function getTireBrands(IndexRequest $request)
    {
        return response()->json(
            TireBrandResource::collection($this->tireBrandRepository->getTireBrands($request->all()))
        );
    }
}
