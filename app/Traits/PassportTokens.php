<?php

namespace App\Traits;

use Laravel\Passport\HasApiTokens as Passport;

trait PassportTokens
{
    use Passport {
        tokens as passportTokens;
    }
}
