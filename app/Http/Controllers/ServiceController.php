<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\IndexRequest;
use App\Http\Requests\Service\UpdateRequest;
use App\Repositories\Service\ServiceRepository;
use App\Http\Resources\Service\ServiceCollection;
use App\Http\Resources\Service\ServiceResource;

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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $service_id)
    {
        $res_partner_id = auth()->user()->res_partner_id;
        $service_data = array_merge($request->validated(), ['user_id' => $res_partner_id]);

        $service = $this->serviceRepository->updateById($service_id, $service_data);

        if ($service) {
            return response()->json(new ServiceResource($service));
        }

        return response()->json(['message' => 'Something went wrong'], 401);
    }
}
