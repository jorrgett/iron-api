<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ValidCredential extends FormRequest
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
            'country_code' => 'required_if:type,phone',
            'phone'        => 'required_if:type,phone',
            'email'        => 'required_if:type,email',
            'code'         => 'required|numeric'
        ];
    }
}
