<?php

namespace App\Http\Requests\Loggin;

use App\Policies\ErrorPolicy;
use Illuminate\Foundation\Http\FormRequest;

class PurgeErrorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new ErrorPolicy(Auth()->user()->id, 'error.delete');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
