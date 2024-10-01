<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseService;
use Kreait\Firebase\Database;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(FirebaseService::class, function ($app) {
            return new FirebaseService();
        });

        $this->app->singleton(Database::class, function ($app) {
            return $app->make(FirebaseService::class)->getDatabase();
        });
    }
}