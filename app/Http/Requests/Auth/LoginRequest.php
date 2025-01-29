<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\CheckPhone;
use App\Rules\VersionCase;
use App\Rules\PlatformCase;
use App\Rules\ValidUsername;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'            => 'email|required_without:phone|exists:users,email',
            'phone'            => ['string', 'required_without:email'],
            'country_code'     => 'string|required_with:phone',
            'password'         => 'required|min:6|max:16',
            'version'          => ['required', 'string', 'regex:/^(\d+)((\.{1}\d+)*)(\.{0})$/', new VersionCase()],
            'platform'         => ['required', 'string', new PlatformCase()],
            'platform_version' => ['required', 'string']
        ];
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @return array
     */
    public function getCredentials()
    {
        $email = $this->get('email');
        $phone = ltrim($this->get('phone'), '0');
        $country_code = $this->get('country_code');

        if ($this->searchUserWithPhone($phone, $country_code)) {
            return [
                'phone' => $phone,
                'password' => $this->get('password')
            ];
        }

        return [
            'email' => $email ?? false,
            'password' => $this->get('password')
        ];
    }

    /**
     * Validate if provided parameter is valid phone.
     *
     * @param $param
     */
    private function searchUserWithPhone($phone, $country_code)
    {
        return User::where('phone', $phone)
            ->where('country_code', $country_code)->first() ?? false;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'version.regex'   => 'The :attribute can only have numbers and dots (1.0.0).',
        ];
    }
}
