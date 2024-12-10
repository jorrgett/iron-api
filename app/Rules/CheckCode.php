<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = "/^[+][1-9]{0,4}$/";

        if(!str_starts_with($value, '+')){
            $fail('The country code must begin with sign (+)');
        }

        if(str_starts_with($value, '+')){
            if(!preg_match($pattern, $value)){
                $fail('The country code format is invalid');
            }
        }
    }
}
