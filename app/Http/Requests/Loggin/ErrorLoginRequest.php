<?php

namespace App\Http\Requests\Loggin;

use App\Policies\ErrorPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ErrorLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new ErrorPolicy(Auth()->user()->id, 'error.store');
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
            'user_id'       => 'required|integer|exists:users,id',
            'date'          => 'required|string',
            'screen'        => 'required|string|min:4|max:200',
            'api'           => 'required|string|min:4|max:200',
            'error_message' => 'required|string|min:10|max:1024'
        ];
    }
}
