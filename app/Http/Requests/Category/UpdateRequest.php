<?php

namespace App\Http\Requests\Category;

use App\Policies\CategoryPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $policy = new CategoryPolicy(Auth()->user()->id, 'category.update');
        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'string|required',
            'action_id' => 'numeric|exists:actions,id',
            'parent_id' => 'sometimes',
            'code'      => 'filled|string'
        ];
    }
}
