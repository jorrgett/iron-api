<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\AWSS3Helper;
use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepository;
use App\Http\Requests\User\DestroyRequest;
use App\Http\Requests\User\FcmTokenRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Requests\User\UploadPhotoRequest;
use App\Http\Requests\User\UpdateByAdminRequest;
use App\Http\Resources\User\UserAuditCollection;
use App\Http\Requests\User\UpdateTermsAndConditionsRequest;

class UserController extends Controller
{
    protected $userRepository;

    /**
     * UserController Constructor.
     *
     * @param UserRepository $userRepository
     */

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        return response()->json(new UserCollection($this->userRepository->getAll($request->all())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $user = $this->userRepository->create($request->all());

        return response()->json(new UserResource($user), 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $id)
    {
        $user = $this->userRepository->UpdateById($id, $request->validated());

        if ($user) {
            return response()->json(new UserResource($user));
        }

        return response()->json(['message' =>  "The password is wrong"], 401);
    }

    public function assignFcmToken(FcmTokenRequest $request)
    {
        $user_id = auth()->id();
        $user = $this->userRepository->updateFcmToken($user_id, $request->all());

        return response()->json(new UserResource($user), 200);
    }

    /**
     * Display the specified resource.
     *
     *@param String $id
     */
    public function show(ShowRequest $request, int $id)
    {
        return response()->json(new UserResource($this->userRepository->getByField('id', $id)), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, $id)
    {
        $this->userRepository->destroy($id);

        return response()->json(['message' => "Tu cuenta ha sido elimina satisfactoriamente"], 200);
    }

    public function uploadPhoto(UploadPhotoRequest $request)
    {
        return response()->json((new AWSS3Helper)->upload($request->image));
    }

    public function updateUserByAdmin(UpdateByAdminRequest $request, $id)
    {
        return response()->json(new UserResource($this->userRepository->UpdateByAdmin($id, $request->validated())));
    }

    public function updateTermsAndConditions(UpdateTermsAndConditionsRequest $request, int $id)
    {
        $user = $this->userRepository->updateTermsAndConditions($id, $request->validated());

        if ($user) {
            return response()->json([
                'message' => 'Términos y condiciones actualizados correctamente.',
                'user' => new UserResource($user)
            ], 200);
        }

        return response()->json(['message' =>  "There was a problem updating the user terms and conditions"], 401);

    }

    public function userFilter(UserFilterRequest $request)
    {
        $size = (int) $request['size'] ?: 10;
        $filter = $request['search'] ?: null;

        $query = User::select(
            'users.id',
            'users.res_partner_id',
            'users.full_name',
            'users.email',
            'vehicles.odoo_id as vehicle_id',
            'vehicles.plate',
            'vehicle_brands.odoo_id as brand_id',
            'vehicle_brands.name as brand_name',
            'vehicle_models.odoo_id as model_id',
            'vehicle_models.name as model_name'
        )
            ->join('services', 'services.owner_id', '=', 'users.res_partner_id')
            ->join('vehicles', 'vehicles.odoo_id', '=', 'services.vehicle_id')
            ->join('vehicle_brands', 'vehicles.vehicle_brand_id', '=', 'vehicle_brands.odoo_id')
            ->join('vehicle_models', 'vehicles.vehicle_model_id', '=', 'vehicle_models.odoo_id');

        if ($request['search']) {
            $response = $query->Orwhere('services.owner_name', 'ilike', "%{$filter}%")
                ->OrWhere('vehicles.plate', 'ilike', "%{$filter}%")
                ->Orwhere('users.full_name', 'ilike', "%{$filter}%")
                ->OrWhere('users.email', 'ilike', "%{$filter}%")
                ->OrWhere('users.res_partner_id', 'like', "%{$filter}%")
                ->groupBy(
                    'vehicles.plate',
                    'users.id',
                    'vehicles.odoo_id',
                    'vehicle_brands.id',
                    'vehicle_brands.name',
                    'vehicle_models.id',
                    'vehicle_models.name',
                )->paginate($size);
        } else {
            $response = $query->groupBy(
                'vehicles.plate',
                'users.id',
                'vehicles.odoo_id',
                'vehicle_brands.id',
                'vehicle_brands.name',
                'vehicle_models.id',
                'vehicle_models.name',
                'services.date'
            )->paginate($size);
        }

        return response()->json(new UserAuditCollection($response));
    }
}
