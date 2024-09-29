<?php

namespace App\Providers;

use App\Models\Note;
use App\Models\User;
use App\Models\Emargement;
use Cloudinary\Cloudinary;
use App\Models\UserFirebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use App\Services\NoteService;
use App\Services\UserService;
use Kreait\Firebase\Database;
use App\Observers\UserObserver;
use App\Models\ApprenantFirebase;
use App\Models\PromotionFirebase;
use App\Services\PromotionService;
use App\Models\ReferentielFirebase;
use App\Services\ApprenantsService;
use App\Services\EmargementService;
use App\Repositories\AuthRepository;
use App\Repositories\NoteRepository;
use App\Repositories\UserRepository;
use App\Services\ReferentielService;
use App\Services\LocalStorageService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\NoteServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Services\FirebaseStorageService;
use App\Interfaces\UserFirebaseInterface;
use App\Repositories\PromotionRepository;
use App\Repositories\ApprenantsRepository;
use App\Repositories\EmargementRepository;
use App\Services\CloudinaryStorageService;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\NoteRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\ReferentielRepository;
use App\Interfaces\ApprenantsModelInterface;
use App\Services\CloudStorageServiceFactory;
use App\Interfaces\PromotionServiceInterface;
use App\Interfaces\ApprenantsServiceInterface;
use App\Interfaces\EmargementServiceInterface;
use App\Interfaces\PromotionFirebaseInterface;
use App\Interfaces\EmergementFirebaseInterface;
use App\Interfaces\ReferentielServiceInterface;
use App\Interfaces\CloudStorageServiceInterface;
use App\Interfaces\PromotionRepositoryInterface;
use App\Interfaces\ReferentielFirebaseInterface;
use App\Interfaces\ApprenantsRepositoryInterface;
use App\Interfaces\EmargementRepositoryInterface;
use App\Interfaces\ReferentielRepositoryInterface;


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

        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);

        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(NoteServiceInterface::class, NoteService::class);

        $this->app->bind(NoteRepositoryInterface::class, function ($app) {
            return new NoteRepository(
                $app->make(Note::class),
                $app->make(Database::class)
            );
        });

        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(PromotionFirebaseInterface::class, PromotionFirebase::class);
        $this->app->bind(PromotionServiceInterface::class, PromotionService::class);
        $this->app->bind('promotion.facade', function ($app) {
            return $app->make(PromotionFirebaseInterface::class);
        });

        $this->app->bind(ReferentielRepositoryInterface::class, ReferentielRepository::class);
        $this->app->bind(ReferentielServiceInterface::class, ReferentielService::class);
        $this->app->bind(ReferentielFirebaseInterface::class, ReferentielFirebase::class);
        $this->app->bind('referentiel.facade', function ($app) {
            return $app->make(ReferentielFirebaseInterface::class);
        });

        $this->app->bind(ApprenantsServiceInterface::class, ApprenantsService::class);
        $this->app->bind(ApprenantsModelInterface::class, ApprenantFirebase::class);
        $this->app->bind(ApprenantsRepositoryInterface::class, ApprenantsRepository::class);
        $this->app->bind('apprenant.facade', function ($app) {
            return $app->make(ApprenantsModelInterface::class);
        });

        $this->app->bind(EmargementRepositoryInterface::class, EmargementRepository::class);
        $this->app->bind(EmargementServiceInterface::class, EmargementService::class);

        $this->app->bind('emargement', function ($app) {
            return $app->make(EmergementFirebaseInterface::class);
        });

        $this->app->singleton(LocalStorageService::class);
        $this->app->bind(CloudStorageServiceInterface::class, function ($app) {
            return CloudStorageServiceFactory::make();
        });

        $this->app->singleton(LocalStorageService::class);

        $this->app->bind(CloudStorageServiceInterface::class, function ($app) {
            return CloudStorageServiceFactory::make();
        });

        $this->app->singleton(Cloudinary::class, function () {
            return new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);
        });

        $this->app->singleton(Storage::class, function () {
            $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
            return $factory->createStorage();
        });

        $this->app->singleton(FirebaseStorageService::class, function ($app) {
            return new FirebaseStorageService($app[Storage::class]);
        });

        $this->app->singleton(CloudinaryStorageService::class, function ($app) {
            return new CloudinaryStorageService($app[Cloudinary::class]);
        });
    }
    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
