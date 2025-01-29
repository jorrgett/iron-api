<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserNotification\UpdateRequest;
use App\Http\Requests\UserNotification\IndexRequest;
use App\Http\Requests\UserNotification\ShowRequest;
use App\Http\Requests\UserNotification\StoreRequest;
use App\Http\Resources\UserNotification\UserByNotificationResource;
use App\Http\Resources\UserNotification\UserNotificationCollection;
use App\Http\Resources\UserNotification\UserNotificationResource;
use App\Repositories\UserNotification\UserNotificationRepository;

class UserNotificationController extends Controller
{
    protected $repository;

    /**
     * UserNotificationController Constructor.
     * 
     * @param UserNotificationRepository $repository
     */
    public function __construct(UserNotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new UserNotificationCollection($this->repository->getAll($request->all())));
    }

    /**
     * Store newly created resources in storage
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $user_notifications = [];

        foreach ($data as $item) {
            $user_notifications[] = $this->repository->create($item);
        }

        return response()->json(UserNotificationResource::collection($user_notifications), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $user_notification = $this->repository->UpdateById($id, $request->validated());

        if ($user_notification) {
            return response()->json(new UserNotificationResource($user_notification));
        }

        return response()->json(['message' => "Something went wrong"], 401);
    }

    /**
     * Display the specified resource.
     * 
     * @param String $id
     */
    public function show(ShowRequest $request, int $id)
    {
        $user_notification = $this->repository->getByField('id', $id);

        if (!$user_notification) {
            return response()->json(['message' => "Could not find a record with ID: {$id}"], 404);
        }

        return response()->json(new UserNotificationResource($user_notification), 200);
    }

    /**
     * Display a listing of the resource by User ID.
     */
    public function getByUser(IndexRequest $request)
    {
        return response()->json(new UserNotificationCollection($this->repository->getByUser($request->all())));
    }

    /**
     * Display a listing of the resource by Notification ID.
     */
    public function getByNotification(IndexRequest $request)
    {
        return response()->json(
            UserByNotificationResource::collection($this->repository->getByNotification($request->all()))
        );
    }

    /**
     * Get summary of notifications for a specific user
     */
    public function getUserNotificationsResume(IndexRequest $request)
    {
        return response()->json($this->repository->getResume(), 200);
    }
}