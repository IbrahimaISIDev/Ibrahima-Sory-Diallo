<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface CloudStorageServiceInterface
{
    public function uploadImage(UploadedFile $file, string $path): string;
}
