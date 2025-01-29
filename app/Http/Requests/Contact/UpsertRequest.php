<?php

namespace App\Http\Requests\Contact;

use App\Rules\CheckCode;
use Illuminate\Foundation\Http\FormRequest;

class UpsertRequest extends FormRequest
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
            'id' => 'required|integer',
            'vat' => 'required|string|min:1|max:25',
            'display_name' => 'required|string|min:1|max:200',
            'email' => 'sometimes',
            'mobile' => 'required',
            'country_code' => ['sometimes', 'string', new CheckCode(), 'max:4'],
            'origin'       => 'required|string|in:autobox,gwmve'
        ];
    }
}
