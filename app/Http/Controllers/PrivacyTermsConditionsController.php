<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PrivacyTermsConditions\PrivacyTermsConditionsRepository;
use App\Http\Requests\PrivacyTermsConditions\IndexRequest;
use App\Http\Requests\PrivacyTermsConditions\StoreRequest;
use App\Http\Requests\PrivacyTermsConditions\UpdateRequest;
use App\Http\Requests\PrivacyTermsConditions\DestroyRequest;
use App\Http\Requests\PrivacyTermsConditions\ShowRequest;
use App\Http\Resources\PrivacyTermsConditions\PrivacyTermsConditionsResource;
use App\Http\Resources\PrivacyTermsConditions\PrivacyTermsConditionsCollection;

class PrivacyTermsConditionsController extends Controller
{
    protected $repository;

    /**
     * PrivacyTermsConditionsController Constructor.
     *
     * @param PrivacyTermsConditionsRepository $repository
     */
    public function __construct(PrivacyTermsConditionsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new PrivacyTermsConditionsCollection($this->repository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $privacyTermsConditions = $this->repository->create($request->all());

        return response()->json(new PrivacyTermsConditionsResource($privacyTermsConditions), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $privacyTermsConditions = $this->repository->updateById($id, $request->validated());

        if ($privacyTermsConditions) {
            return response()->json(new PrivacyTermsConditionsResource($privacyTermsConditions));
        }

        return response()->json(['message' => "Could not find a record with ID: $id"], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, int $id)
    {
        $privacyTermsConditions = $this->repository->getByField('id', $id);
    
        if (!$privacyTermsConditions) {
            return response()->json(['message' => "No se encontró ningún registro con id {$id}"], 404);
        }
    
        return response()->json(new PrivacyTermsConditionsResource($privacyTermsConditions), 200);
    }       

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, int $id)
    {
        try {
            $deleted = $this->repository->destroy($id);

            if ($deleted) {
                return response()->json(['message' => "Record deleted successfully"], 200);
            } else {
                return response()->json(['message' => "Could not delete the record"], 400);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => "Could not find a record with ID: $id"], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => "An error occurred while trying to delete the record"], 500);
        }
    }

    /**
     * Get the last active record by type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastActiveByType()
    {
        $result = $this->repository->getLastActiveByType();

        return response()->json(PrivacyTermsConditionsResource::collection($result));
    }
}
