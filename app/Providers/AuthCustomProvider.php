<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Yaml;
use App\Interfaces\AuthServiceInterface;

class AuthCustomProvider extends ServiceProvider
{
    public function register()
    {
        $yamlFile = base_path('config/authentication.yaml');
        $config = Yaml::parseFile($yamlFile);

        $authServiceClass = $config['authentication']['provider'];

        $this->app->bind(AuthServiceInterface::class, function ($app) use ($authServiceClass) {
            return $app->make($authServiceClass);
        });
    }

    public function boot()
    {
        //
    }
}
