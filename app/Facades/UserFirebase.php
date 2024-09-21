<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserFirebase extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user.firebase';
    }
}