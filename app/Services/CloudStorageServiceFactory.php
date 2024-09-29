<?php

namespace App\Services;

use App\Interfaces\CloudStorageServiceInterface;

class CloudStorageServiceFactory
{
    public static function make(): CloudStorageServiceInterface
    {
        $storageDriver = config('filesystems.cloud');

        if ($storageDriver === 'cloudinary') {
            return app(CloudinaryStorageService::class);
        }

        return app(FirebaseStorageService::class);
    }
}
