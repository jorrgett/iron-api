<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserList\FilterRequest;
use App\Http\Resources\UserList\UserListResource;
use App\Repositories\UserList\UserListRepository;

class UserListController extends Controller
{
    protected $repository;

    public function __construct(UserListRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     */
    public function filterServices(FilterRequest $request)
    {
        return response()->json(
            UserListResource::collection($this->repository->filterServices($request->all()))
        );
    }
}