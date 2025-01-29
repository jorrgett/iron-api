<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Helpers\FindHelper;
use App\Policies\UserPolicy;
use App\Rules\CheckCode;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $id = (int) $this->route()->Parameter('user');
        $user = User::findOrFail($id);
        $policy = new UserPolicy(Auth()->user()->id, 'user.update', $user);

        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $id = $this->route()->Parameter('user');
        $user = FindHelper::recordInResource(User::class, $id);
        # ^[^<>]*$
        return [
            'full_name'           => "filled|regex:/^[^?#$%&*()'<>]*$/|max:50",
            'email'               => 'filled|email|unique:users,email,' . $user->id,
            'new_password'            => ['sometimes', Password::min(6)->max(16)],
            'confirm_password'    => 'same:new_password|required_with:new_password',
            'current_password'    => 'required',
            'res_partner_id'      => 'filled|integer|unique:users,res_partner_id,' . $user->id,
            'country_code'        => ['filled', 'string',  new CheckCode(), 'max:4'],
            'phone'               => 'filled|string|regex:/^[0-9]+$/|min:10|max:14|unique:users,phone,' . $user->id, # TODO: Revisar esto
            'avatar_url'          => 'url|sometimes',
            'avatar_path'         => 'required_with:avatar_url|string',
            'language'            => 'filled|in:ES,EN',
            'email_verified'      => 'boolean|required_with:email',
            'phone_verified'      => 'boolean|required_with:phone',
            'role_id'             => 'sometimes|exists:roles,id',
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
