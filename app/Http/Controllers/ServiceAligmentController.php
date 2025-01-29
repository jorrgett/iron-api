<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceAligment\IndexRequest;
use App\Repositories\ServiceAligment\ServiceAligmentRepository;
use App\Http\Resources\ServiceAligment\ServiceAligmentCollection;

class ServiceAligmentController extends Controller
{
    protected $serviceAligmentRepository;

    /**
     * ServiceAligmentController Constructor.
     *
     * @param ServiceAligmentRepository $serviceAligmentRepository
     */

    public function __construct(ServiceAligmentRepository $serviceAligmentRepository)
    {
        $this->serviceAligmentRepository = $serviceAligmentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ServiceAligmentCollection($this->serviceAligmentRepository->getAll($request->all())));
    }
}
