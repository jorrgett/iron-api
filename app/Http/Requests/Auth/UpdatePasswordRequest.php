<?php

namespace App\Http\Requests\Auth;

use App\Rules\CheckCode;
use App\Rules\UpdatePhone;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'        => 'required_without:phone|email|exists:users,email',
            'phone'        => ['string', 'regex:/^[0-9]+$/', 'required_without:email', new UpdatePhone($this->get('country_code'))],
            'country_code' => ['required_with:phone', new CheckCode()]
        ];
    }
}
