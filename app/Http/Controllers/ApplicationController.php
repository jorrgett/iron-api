<?php

namespace App\Http\Controllers;

use App\Repositories\Application\ApplicationRepository;
use App\Http\Requests\Application\{
    IndexRequest,
    StoreRequest,
    UpdateRequest,
    DestroyRequest,
};
use App\Http\Resources\Application\{
    ApplicationResource,
    ApplicationCollection
};

class ApplicationController extends Controller
{
    protected $applicationRepository;

    /**
     * CategoryController Constructor.
     *
     * @param ApplicationRepository $applicationRepository
     */

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new ApplicationCollection($this->applicationRepository->getAll($request->all())));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new ApplicationResource($this->applicationRepository->create($request->all())), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        return response()->json(new ApplicationResource($this->applicationRepository->UpdateById($id, $request->all())));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return response()->json($this->applicationRepository->destroy($id, $request->all()), 204);
    }

    /**
     * Get the latest enabled version for each platform.
     */
    public function getLatestEnabledVersions()
    {
        $latestVersions = $this->applicationRepository->getLatestEnabledVersionsByPlatform();
        return response()->json($latestVersions);
    }

    /**
     * Get available versions for a platform.
     */
    public function getAvailableVersions($platform)
    {
        $availableVersions = $this->applicationRepository->getAvailableVersionsByPlatform($platform);
        return response()->json($availableVersions);
    }
}
