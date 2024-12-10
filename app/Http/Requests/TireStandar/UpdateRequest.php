<?php

namespace App\Http\Requests\TireStandar;

use App\Policies\TireStandarPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new TireStandarPolicy(Auth()->user()->id, 'tire_otd_standar.update');
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
            'tire_size' => 'filled|string|min:2|max:50',
            'otd'       => 'filled|numeric'
        ];
    }
}
