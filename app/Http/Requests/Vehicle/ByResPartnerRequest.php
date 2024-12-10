<?php

namespace App\Http\Requests\Vehicle;

use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Http\FormRequest;

class ByResPartnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new VehiclePolicy(Auth()->user()->id, 'vehicle.by_res_partner');
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
            'res_partner_id' => 'required|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'res_partner_id.required' => 'The res_partner_id field is mandatory in the query.',
            'res_partner_id.integer' => 'The res_partner_id field must be an integer.',
        ];
    }
}
