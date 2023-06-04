<?php

namespace App\Rules;

use App\Models\TeamInvitation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InvitedUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (TeamInvitation::where('email', $value)->doesntExist()) {
            $fail('You are not invited to register.');
        }
    }
}
