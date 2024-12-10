<?php

namespace App\Http\Controllers;

use App\Http\Requests\Topic\DestroyRequest;
use App\Http\Requests\Topic\IndexRequest;
use App\Http\Requests\Topic\ShowRequest;
use App\Http\Resources\Topic\TopicCollection;
use App\Http\Resources\Topic\TopicResource;
use App\Repositories\Topic\TopicRepository;

class TopicController extends Controller
{
    protected $repository;

    /**
     * Topic Constructor.
     * 
     * @param TopicRepository $repository
     */
    public function __construct(TopicRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new TopicCollection($this->repository->getAll($request->all())));
    }

    /**
     * Display the specific resource.
     */
    public function show(ShowRequest $request, int $id)
    {
        $topic = $this->repository->getByField('id', $id);

        if (!$topic) {
            return response()->json(['message' => "Could not find a record with ID: {$id}"], 404);
        }

        return response()->json(new TopicResource($topic), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return response()->json($this->repository->destroy($id, $request->all()), 204);
    }
}