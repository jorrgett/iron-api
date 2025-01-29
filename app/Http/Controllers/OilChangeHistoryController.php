<?php

namespace App\Http\Controllers;

use App\Http\Requests\OilHistory\IndexRequest;
use App\Http\Resources\OilHistory\OilHistoryCollection;
use App\Repositories\OilHistory\OilChangeHistoryRepository;

class OilChangeHistoryController extends Controller
{
    protected $OilChangeHistoryRepository;

    /**
     * Oil Change History Controller Constructor.
     *
     * @param OilChangeHistoryRepository $OilChangeHistoryRepository
     */

    public function __construct(OilChangeHistoryRepository $OilChangeHistoryRepository)
    {
        $this->OilChangeHistoryRepository = $OilChangeHistoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new OilHistoryCollection($this->OilChangeHistoryRepository->getAll($request->all())));
    }
}
