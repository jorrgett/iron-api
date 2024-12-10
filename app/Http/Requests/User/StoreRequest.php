<?php

namespace App\Http\Requests\User;

use App\Policies\UserPolicy;
use App\Rules\CheckCode;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new UserPolicy(Auth()->user()->id, 'user.store');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name'           => "string|regex:/^[^?#$%&*()'<>]*$/|max:50",
            'email'               => 'required|email|unique:users',
            'password'            => ['required', Password::min(6)
                ->letters()
                ->mixedCase()
                ->numbers()],
            'res_partner_id'      => 'required|integer|unique:users,res_partner_id',
            'country_code'        => ['required', 'string',  new CheckCode(), 'max:4'],
            'phone'               => 'required|regex:/[0-9]/|not_regex:/[a-z]/|min:10|max:14|unique:users',
            'email_verified_at'   => 'sometimes|datetime',
            'avatar_url'          => 'url|sometimes',
            'avatar_path'         => 'required_with:avatar_url|string',
            'language'            => 'required|in:EN,ES'
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
