<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password as PasswordRule;
use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return [
            'required',
            'string',
            (new Password),
            PasswordRule::min(8)->uncompromised(),
            'confirmed'
        ];
    }
}
