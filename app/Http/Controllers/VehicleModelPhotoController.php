<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FilevaultHelper;
use App\Models\VehicleModelPhoto;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\VehiclePhotoModel\Resource;
use App\Http\Resources\VehiclePhotoModel\Collection;
use App\Http\Requests\VehicleModelPhoto\IndexRequest;
use App\Http\Requests\VehicleModelPhoto\StoreRequest;
use App\Http\Requests\VehicleModelPhoto\UpdateRequest;
use App\Http\Requests\VehicleModelPhoto\DestroyRequest;

class VehicleModelPhotoController extends Controller
{
    protected $filevaultHelper;

    public function __construct(FilevaultHelper $filevaultHelper)
    {
        $this->filevaultHelper = $filevaultHelper;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $photos = VehicleModelPhoto::filter()
            ->where('is_active', true)
            ->get();

        return response()->json(new Collection($photos));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $this->prepareData($request->validated());

        if ($data){
            $vehicleModelPhoto = VehicleModelPhoto::create($data);
            return response()->json(new Resource($vehicleModelPhoto), 201);
        }

        return response()->json(['message' => 'Whoops, there was a problem loading the image, contact support.'], 400);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $vehicleModelPhoto = VehicleModelPhoto::findOrFail($id);
        $data = $this->prepareData($request->validated(), $vehicleModelPhoto);

        $vehicleModelPhoto->update($data);

        return response()->json(new Resource($vehicleModelPhoto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRequest $request, string $id)
    {
        $vehicleModelPhoto = VehicleModelPhoto::findOrFail($id);
        $this->deletePhoto($vehicleModelPhoto);

        $vehicleModelPhoto->update(['is_active' => false]);

        return response()->json([], 204);
    }

    /**
    * Prepare data for creating or updating a record.
    */
    private function prepareData(array $data, ?VehicleModelPhoto $existingPhoto = null)
    {
        if (!isset($data['file'])) {
            return $data;
        }

        if ($existingPhoto) {
            $this->deletePhoto($existingPhoto);
        }

        $photo = $this->filevaultHelper->uploadFile($data['file']);

        if ($photo['success'] == false) {
            return false;
        }
        
        $data['photo_url'] = $photo['data']['url'];
        $data['photo_path'] = $photo['data']['file_path'];
        $data['is_active'] = true;

        unset($data['file']);

        return $data;
    }

    /**
     * Delete the photo from storage.
     */
    private function deletePhoto(VehicleModelPhoto $photoModelVehicle): void
    {
        if ($photoModelVehicle->photo_path) {
            $this->filevaultHelper->removeFile($photoModelVehicle->photo_path);
        }
    }
}
