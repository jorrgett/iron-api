<?php

namespace App\Http\Requests\Auth;

use App\Rules\CheckCode;
use App\Rules\CheckPhone;
use Illuminate\Foundation\Http\FormRequest;

class CheckCredentials extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'         => 'required|in:phone,email',
            'country_code' => ['required_if:type,phone', new CheckCode()],
            'phone'        => ['required_if:type,phone', 'regex:/^[0-9]+$/', new CheckPhone($this->get('country_code'))],
            'email'        => 'required_if:type,email|unique:users,email'
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
            'phone.regex'  => 'The :attribute must be composed only of numbers [0-9]',
            'phone.unique' => 'The :attribute already in use with another user',
            'email.unique' => 'The :attribute already in use with another user',
        ];
    }
}
