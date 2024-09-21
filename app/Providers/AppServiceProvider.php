<?php

namespace App\Providers;

use App\Models\UserFirebase;
use App\Services\UserService;
use App\Models\PromotionFirebase;
use App\Models\ReferentielFirebase;
use Illuminate\Database\Connection;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserFirebaseInterface;
use App\Repositories\PromotionRepository;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ReferentielRepository;
use App\Interfaces\PromotionFirebaseInterface;
use App\Interfaces\ReferentielFirebaseInterface;
use App\Interfaces\Repositories\PromotionRepositoryInterface;
use App\Interfaces\Repositories\ReferentielRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserFirebaseInterface::class, UserFirebase::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind('user.firebase', function ($app) {
            return $app->make(UserFirebaseInterface::class);
        });
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(PromotionFirebaseInterface::class, PromotionFirebase::class);
        // $this->app->bind(PromotionServiceInterface::class, PromotionService::class);
        $this->app->bind('promotion.facade', function ($app) {
            return $app->make(PromotionFirebaseInterface::class);
        });
        
        $this->app->bind(ReferentielRepositoryInterface::class, ReferentielRepository::class);
        // $this->app->bind(ReferentielServiceInterface::class, ReferentielService::class);
        $this->app->bind(ReferentielFirebaseInterface::class, ReferentielFirebase::class);
        $this->app->bind('referentiel.facade', function ($app) {
            return $app->make(ReferentielFirebaseInterface::class);
        });
    }
    public function boot()
    {
        Connection::resolverFor('firebase', function ($connection, $database, $prefix, $config) {
            return $config['credentials'];
        });
    }
}
