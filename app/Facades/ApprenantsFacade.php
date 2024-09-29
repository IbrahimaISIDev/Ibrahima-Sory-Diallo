<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ApprenantsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'apprenant.facade';
    }
}