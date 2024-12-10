<?php

namespace App\Http\Requests\AppWarning;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'filled|string|min:1|max:50',
            'description' => 'filled|string|min:1|max:200',
            'threshold' => 'filled|integer'
        ];
    }
}
