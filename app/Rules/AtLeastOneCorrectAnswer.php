<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneCorrectAnswer implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {
        // Check if at least one checkbox is checked (value is `1`)
        return is_array($value) && in_array('1', $value);
    }

    public function message()
    {
        return 'Each question must have at least one correct answer.';
    }
}
