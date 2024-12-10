<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppParameters\DestroyRequest;
use App\Http\Requests\AppParameters\IndexRequest;
use App\Http\Requests\AppParameters\ShowRequest;
use App\Http\Requests\AppParameters\StoreRequest;
use App\Http\Requests\AppParameters\UpdateRequest;
use App\Http\Resources\AppParameters\AppParametersCollection;
use App\Http\Resources\AppParameters\AppParametersResource;
use App\Repositories\AppParameters\AppParametersRepository;

class AppParametersController extends Controller
{
    protected $repository;

    /**
     * AppParametersController Constructor.
     *
     * @param AppParametersRepository $repository
     */
    public function __construct(AppParametersRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new AppParametersCollection($this->repository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $appParameters = $this->repository->create($request->all());

        return response()->json(new AppParametersResource($appParameters), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $appParameters = $this->repository->updateById($id, $request->validated());

        if ($appParameters) {
            return response()->json(new AppParametersResource($appParameters));
        }

        return response()->json(['message' => "Could not find a record with ID: $id"], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, int $id)
    {
        $appParameters = $this->repository->getByField('id', $id);
    
        if (!$appParameters) {
            return response()->json(['message' => "Could not find a record with ID: {$id}"], 404);
        }
    
        return response()->json(new AppParametersResource($appParameters), 200);
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
}