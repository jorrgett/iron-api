<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Rules\CheckCode;
use App\Helpers\FindHelper;
use App\Policies\AdminPolicy;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateByAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        $policy = new AdminPolicy(Auth()->user()->id, '');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route()->Parameter('id');
        $user = FindHelper::recordInResource(User::class, $id);
        # ^[^<>]*$
        return [
            'full_name'           => "filled|regex:/^[^?#$%&*()'<>]*$/|max:50",
            'email'               => 'filled|email|unique:users,email,' . $user->id,
            'password'              => ['filled', Password::min(6)
                ->letters()
                ->mixedCase()
                ->numbers()],
            'res_partner_id'      => 'filled|integer|unique:users,res_partner_id,' . $user->id,
            'country_code'        => ['filled', 'string',  new CheckCode(), 'max:4'],
            'phone'               => 'filled|string|regex:/^[0-9]+$/|min:10|max:14|unique:users,phone,' . $user->id, # TODO: Revisar esto
            'avatar_url'          => 'url|sometimes',
            'avatar_path'         => 'required_with:avatar_url|string',
            'language'            => 'filled|in:ES,EN',
            'role_id'             => 'required|nullable',
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
            'phone.regex' => 'The :attribute can only have numbers',
            'phone.not_gex' => "The :attribute can't contain letters or special characters",
            'res_partner_id' => "The :attribute is already associated with another account"
        ];
    }
}
