<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class LocalStorageService
{
    public function storeImageLocally($imageBase64, $folder = 'images/users', $filename)
    {
        $imageContent = base64_decode($imageBase64);

        $filePath = $folder . '/' . $filename;

        Storage::disk('local')->put($filePath, $imageContent);

        return $filePath;
    }
}
