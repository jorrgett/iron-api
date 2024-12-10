<?php

namespace App\Http\Requests\Application;

use App\Policies\ApplicationPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new ApplicationPolicy(Auth()->user()->id);
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
            'version'  => 'string|filled',
            'platform' => 'string|filled',
            'enable'   => 'boolean',
            'note'     => 'string|filled'
        ];
    }
}
