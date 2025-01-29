<?php

namespace App\Http\Controllers;

use App\Models\HeatMap;
use App\Models\Setting;
use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Role\IndexRequest;
use App\Http\Requests\Role\StoreRequest;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Requests\Role\DestroyRequest;
use App\Http\Resources\Error\ErrorResource;
use App\Http\Resources\Error\ErrorCollection;
use App\Http\Requests\Loggin\ErrorLoginRequest;
use App\Http\Requests\Loggin\IndexErrorRequest;
use App\Http\Requests\Loggin\PurgeErrorRequest;
use App\Http\Requests\Settings\PingUserRequest;
use App\Http\Requests\Role\AssignPermissionRequest;
use App\Http\Requests\Settings\PingUserBulkRequest;

class SettingsController extends Controller
{
    public function getSettings(Request $request)
    {
        return response()->json(Setting::findOrFail(1), 200);
    }

    public function pingUser(PingUserRequest $request)
    {
        $data = $request->validated();
        $data['event_date'] = now();
        $data['user_id'] = auth()->user()->id;

        HeatMap::create($data);

        return response()->json([
            'message' => 'Statistics have been recorded'
        ]);
    }

    public function pingUserBulk(PingUserBulkRequest $request)
    {
        $validatedData = $request->validated();
        $userId = auth()->user()->id;
        $failedIds = [];

        foreach ($validatedData as $record) {
            try {
                $dataToInsert = [
                    'event_date' => $record['event_date'] ?? now(),
                    'page' => $record['page'],
                    'object' => $record['object'],
                    'user_id' => $userId
                ];

                HeatMap::create($dataToInsert);
            } catch (\Exception $e) {
                $failedIds[] = $record['id'];
            }
        }

        if (count($failedIds) > 0) {
            return response()->json([
                'message' => 'Some records failed to be inserted',
                'failed_ids' => $failedIds
            ], 400);
        }

        return response()->json([
            'message' => 'All records have been successfully inserted'
        ], 200);
    }

    public function getAllLogs(IndexErrorRequest $request)
    {
        $logs = ErrorLog::query();
        $screen = $request['screen'] ?: null;
        $user_id = $request['user_id'] ?: null;
        $size = $request['size'] ?: 10;

        if ($user_id) {
            $response =  $logs->where('user_id', $user_id);
        }

        if ($screen) {
            $response = $logs->where('screen', $screen);
        }

        $response = $logs->orderByDesc('date');

        return response()->json(new ErrorCollection($response->paginate($size)));
    }

    public function storeLog(ErrorLoginRequest $request)
    {
        $data = $request->validated();
        $data['sequence_id'] = DB::select("select nextval('error_logs_sequence')")[0]->nextval;
        $new_logs = ErrorLog::create($data);

        return response()->json(new ErrorResource($new_logs));
    }

    public function purgeLogs(PurgeErrorRequest $request)
    {
        $logs = ErrorLog::query();
        $from = $request['from'] ?: null;
        $to = $request['to'] ?: null;
        $user_id = $request['user_id'] ?: null;

        if ($from & $to) {
            $logs->whereBetween('date', [$from, $to])->delete();
        }

        if ($user_id) {
            $logs->where('user_id', $user_id)->delete();
        }

        $logs->delete();

        return response()->json([], 204);
    }

    public function getRoles(IndexRequest $request)
    {
        return response()->json(Role::all());
    }
    public function createRoles(StoreRequest $request)
    {
        $new_role = Role::create($request->validated());
        return response()->json($new_role, 201);
    }
    public function updateRole(UpdateRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());
        return response()->json($role);
    }
    public function deleteRole(DestroyRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json([], 204);
    }
    public function getPermissions(IndexRequest $indexRequest)
    {
        return response()->json(Permission::all());
    }
    public function assignPermissions(AssignPermissionRequest $request)
    {
        $find_role = Role::findById($request->role_id);

        return $find_role->givePermissionTo($request->permissions);
    }
}
