<?php

namespace App\Http\Controllers;

use App\Helpers\CustomPaginator;
use App\Models\AppWarning;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AppWarning\ShowRequest;
use App\Http\Requests\AppWarning\IndexRequest;
use App\Http\Requests\AppWarning\StoreRequest;
use App\Http\Requests\AppWarning\UpdateRequest;
use App\Http\Requests\AppWarning\DestroyRequest;
use App\Http\Requests\AppWarning\WarningHealingRequest;
use App\Http\Requests\AppWarning\WarningResumeRequest;
use App\Http\Resources\AppWarning\AppWarningResource;
use App\Http\Resources\AppWarning\AppWarningCollection;
use App\Http\Resources\AppWarning\AutoHealingCollection;

class AppWarningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $page = !empty($request['size']) ? (int) $request['size'] : 10;

        return response()->json(new AppWarningCollection(AppWarning::paginate($page)));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return response()->json(new AppWarningResource(AppWarning::create($request->validated())), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request, $id)
    {
        return response()->json(new AppWarningResource(AppWarning::findOrFail($id)));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $appWarning = AppWarning::findOrFail($id);
        $appWarning->fill($request->validated());
        $appWarning->save();

        return response()->json(new AppWarningResource(AppWarning::findOrFail($id)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, string $id)
    {
        AppWarning::findOrFail($id)->delete();
        return response()->json([], 204);
    }


    public function autoHealingTires(WarningHealingRequest $request)
    {
        $search = request('search') ?? null;

        if ($search != null) {
            $results = DB::select("SELECT * FROM warning_autohealing_tires WHERE email ilike '%$search%' OR plate ilike '%$search%' OR CAST(service_id AS TEXT) ILIKE '%$search%' ORDER BY service_date DESC");
        } else {
            $results = DB::select("SELECT * FROM warning_autohealing_tires ORDER BY service_date DESC");
        }

        return response()->json(new AutoHealingCollection((new CustomPaginator($request))->paginate($results)));
    }

    public function appWarningSummary(WarningResumeRequest $request)
    {
        return DB::select('SELECT * FROM get_app_warnings_resume()');
    }
}
