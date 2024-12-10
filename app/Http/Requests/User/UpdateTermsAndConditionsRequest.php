<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Policies\UserPolicy;

class UpdateTermsAndConditionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $id = (int) $this->route()->Parameter('id');
        $user = User::findOrFail($id);
        $policy = new UserPolicy(Auth()->user()->id, 'user.update', $user);

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
            'terms_and_conditions_id' => 'sometimes|integer|exists:privacy_terms_conditions,id',
            'legal_disclaimer_id' => 'sometimes|integer|exists:privacy_terms_conditions,id',
            'privacy_policy_id'   => 'sometimes|integer|exists:privacy_terms_conditions,id',
            'legals_accepted'     => ['sometimes', 'accepted'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'terms_and_conditions_id.integer'   => 'The Terms and Conditions ID must be an integer.',
            'terms_and_conditions_id.exists'    => 'The terms and conditions ID provided does not exist.',
            'legal_disclaimer_id.integer'       => 'The legal notice ID must be an integer.',
            'legal_disclaimer_id.exists'        => 'The legal notice ID provided does not exist.',
            'privacy_policy_id.integer'         => 'The privacy policy ID must be an integer.',
            'privacy_policy_id.exists'          => 'The privacy policy ID provided does not exist.',
            'legals_accepted.accepted'          => 'You must accept the terms and conditions.',
        ];
    }
}
