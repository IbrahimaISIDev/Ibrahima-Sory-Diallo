<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class EmargementFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'emargement';
    }
}