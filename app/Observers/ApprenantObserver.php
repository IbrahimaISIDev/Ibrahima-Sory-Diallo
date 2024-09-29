<?php

namespace App\Observers;

use App\Models\ApprenantFirebase;
use App\Events\ApprenantCreated;

class ApprenantObserver
{
    public function created(ApprenantFirebase $apprenant)
    {
        event(new ApprenantCreated($apprenant));
    }
}