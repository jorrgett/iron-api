<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Helpers\FindHelper;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $id = (int) $this->route()->Parameter('user');
        $user = User::findOrFail($id);
        $policy = new UserPolicy(Auth()->user()->id, 'user.show', $user);

        return $policy->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
