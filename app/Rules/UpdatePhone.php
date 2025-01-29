<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdatePhone implements ValidationRule
{
    private $country_code;

    public function __construct($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = ltrim($value, '0');
        $user = User::where('phone', $value)
            ->where('country_code', $this->country_code)->first();

        if (!$user) {
            $fail('The selected phone is not associated with any registered user');
        }
    }
}
