<?php

namespace App\Services;

use Kreait\Firebase\Storage;
use Illuminate\Http\UploadedFile;
use App\Interfaces\CloudStorageServiceInterface;

class FirebaseStorageService implements CloudStorageServiceInterface
{
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function uploadImage(UploadedFile $file, string $path): string
    {
        $filePath = $path . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
        $bucket = $this->storage->getBucket();
        
        $bucket->upload(
            file_get_contents($file->getRealPath()),
            ['name' => $filePath] 
        );

        $imageReference = $bucket->object($filePath);
        return $imageReference->signedUrl(new \DateTime('next year'));
    }
}
