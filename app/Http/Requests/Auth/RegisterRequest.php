<?php

namespace App\Http\Requests\Auth;

use App\Rules\CheckCode;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name'            => "string|required|regex:/^[^?#$%&*()'<>]*$/|min:6|max:50",
            'email'                => 'required_without:phone|email|unique:users',
            'password'             => ['required', 'min:6', 'max:16'],
            'res_partner_id'       => 'sometimes|integer|unique:users,res_partner_id',
            'country_code'         => ['required_with:phone', 'string',  new CheckCode(), 'max:4'],
            'phone'                => 'required_without:email|regex:/[0-9]/|not_regex:/[a-z]/|min:10|max:12|unique:users,phone',
            'avatar_url'           => 'url|sometimes',
            'legals_accepted'      => ['sometimes', 'accepted'],
            'terms_and_conditions_id' => 'sometimes|integer|exists:privacy_terms_conditions,id',
            'legal_disclaimer_id'  => 'sometimes|integer|exists:privacy_terms_conditions,id',
            'privacy_policy_id'    => 'sometimes|integer|exists:privacy_terms_conditions,id',
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
            'full_name.regex' => 'The :attribute can only have letters, accents, spaces or umlauts',
            'phone.regex' => 'The :attribute can only have numbers',
            'phone.not_gex' => "The :attribute can't contain letters or special characters",
            'res_partner_id' => "The :attribute is already associated with another account",
            'legals_accepted.accepted' => 'You must accept the legal terms to register.'
        ];
    }
}
