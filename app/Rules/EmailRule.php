<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // On peut utiliser la fonction filter_var pour valider l'email
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('L\'adresse e-mail n\'est pas valide.');
        }

        // Si vous voulez appliquer une validation plus stricte, vous pouvez utiliser une regex comme suit:
        // $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        // if (!preg_match($pattern, $value)) {
        //     $fail('L\'adresse e-mail n\'est pas valide.');
        // }
    }
}