<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use App\Interfaces\CloudStorageServiceInterface;

class CloudinaryStorageService implements CloudStorageServiceInterface
{
    protected $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function uploadImage(UploadedFile $file, string $path): string
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => $path]
        );
        
        return $result['secure_url'];
    }
}