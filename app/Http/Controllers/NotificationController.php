<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\DestroyRequest;
use App\Http\Requests\Notification\IndexRequest;
use App\Http\Requests\Notification\ShowRequest;
use App\Http\Requests\Notification\StoreRequest;
use App\Http\Requests\Notification\UpdateRequest;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\Notification\NotificationResource;
use App\Repositories\Notification\NotificationRepository;

class NotificationController extends Controller
{
    protected $repository;

    /**
     * NotificationController Constructor.
     *
     * @param NotificationRepository $notificationRepository
     */

    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new NotificationCollection($this->repository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new NotificationResource($this->repository->create($request->all())), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $notification = $this->repository->UpdateById($id, $request->validated());

        if ($notification) {
            return response()->json(new NotificationResource($notification));
        }

        return response()->json(['message' => "Could not find a record with ID: $id"], 404);
    }

    /**
     * Display the specific resource.
     */
    public function show(ShowRequest $request, int $id)
    {
        $notification = $this->repository->getByField('id', $id);

        if (!$notification) {
            return response()->json(['message' => "Could not find a record with ID: {$id}"], 404);
        }

        return response()->json(new NotificationResource($notification), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $id)
    {
        return response()->json($this->repository->destroy($id, $request->all()), 204);
    }
}
