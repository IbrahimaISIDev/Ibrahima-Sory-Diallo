<?php

namespace App\Observers;

use App\Events\UserCreated;
use App\Models\User;
use App\Facades\UserFirebase;

class UserObserver
{
    public function created(User $User)
    {
        $userFirebase = UserFirebase::find($User->id) ?? null;
        // event(new UserCreated($User, $userFirebase));
    }
}

