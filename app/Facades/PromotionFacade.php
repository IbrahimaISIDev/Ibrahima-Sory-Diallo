<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PromotionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'promotion.facade';
    }
}