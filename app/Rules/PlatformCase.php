<?php

namespace App\Rules;

use Closure;
use App\Models\Application;
use Illuminate\Contracts\Validation\ValidationRule;

class PlatformCase implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $application = Application::where('platform', $value)
            ->where('enable', true)->first();

        if (!$application) {
            $fail('The platform sent does not match the ones available');
        }
    }
}
