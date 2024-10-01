<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Http\UploadedFile;
use App\Jobs\UploadProfilePictureJob;

class HandleProfilePictureUpload
{
    public function handle(UserCreated $event)
    {
            UploadProfilePictureJob::dispatch($event->userMysql, $event->userFirebase);
    }
}