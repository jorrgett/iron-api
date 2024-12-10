<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidUsername implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = "/^[0][1-9]{2,3}[0-9]{3,3}[0-9]{2,2}[0-9]{2,2}$/";

        if (str_contains($value, '@')) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $fail('The email format you are trying to enter is invalid');
            }

            if (!(User::where('email', $value)->first())) {
                $fail('The credentials are invalid');
            }
        }

        if (!str_starts_with($value, 0)) {
            if (!preg_match($pattern, $value)) {
                $fail('The phone format is invalid');
            }

            if (!(User::where('phone', $value)->first())) {
                $fail('The credentials are invalid');
            }
        }
    }
}
