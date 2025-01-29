<?php

namespace App\Http\Requests\Vehicle;

use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new VehiclePolicy(Auth()->user()->id, 'vehicle.update');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => 'filled|string',
            'icon'     => 'filled|string'
        ];
    }
}
