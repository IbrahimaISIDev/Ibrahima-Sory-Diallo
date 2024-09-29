<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Emargement;

class EmargementServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('emargement', function ($app) {
            return new Emargement();
        });
    }
}