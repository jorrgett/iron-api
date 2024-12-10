<?php

namespace App\Http\Requests\VehicleModelPhoto;

use App\Policies\VehicleModelPhotoPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new VehicleModelPhotoPolicy(Auth()->user()->id, 'vehicle_model_photo.update');
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
            'brand_id' => 'integer|filled|exists:vehicle_brands,odoo_id',
            'model_id' => 'integer|filled|exists:vehicle_models,odoo_id',
            'year'     => 'integer|filled',
            'color'    => 'string|filled',
            'file'     => 'filled|image|mimes:png'
        ];
    }
}
