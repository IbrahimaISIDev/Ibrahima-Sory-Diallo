<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Cloudinary\Laravel\CloudinaryServiceProvider;

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'port' => env('PORT', 8000),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([

        App\Providers\AuthCustomProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // CloudinaryServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\FirebaseServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        App\Providers\EmargementServiceProvider::class,
        L5Swagger\L5SwaggerServiceProvider::class,
        Kreait\Laravel\Firebase\ServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Emargement' => App\Facades\EmargementFacade::class,
    ])->toArray(),

];
