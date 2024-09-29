<?php

namespace App\Traits;

use Laravel\Sanctum\HasApiTokens as Sanctum;

trait SanctumTokens
{
    use Sanctum {
        tokens as sanctumTokens;
    }
}

