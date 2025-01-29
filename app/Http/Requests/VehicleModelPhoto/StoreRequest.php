<?php

namespace App\Http\Requests\VehicleModelPhoto;

use App\Policies\VehicleModelPhotoPolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new VehicleModelPhotoPolicy(Auth()->user()->id, 'vehicle_model_photo.store');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => 'integer|required|exists:vehicle_brands,odoo_id',
            'model_id' => 'integer|required|exists:vehicle_models,odoo_id',
            'year'     => 'integer|required',
            'color'    => 'string|required',
            'file'     => 'required|image|mimes:png'
        ];
    }
}
