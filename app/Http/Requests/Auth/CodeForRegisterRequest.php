<?php

namespace App\Http\Requests\Auth;

use App\Rules\CheckCode;
use App\Rules\UpdatePhone;
use Illuminate\Foundation\Http\FormRequest;

class CodeForRegisterRequest extends FormRequest
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
            'vat'          => 'required_without:phone|regex:/^[VJE]-\d{7,9}(-\d+)?$/',
            'phone'        => ['string', 'regex:/^[0-9]+$/', 'required_without:vat', new UpdatePhone($this->get('country_code'))],
            'country_code' => ['required_with:phone', new CheckCode()]
        ];
        
    }
}
