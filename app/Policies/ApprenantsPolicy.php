<?php

namespace App\Policies;

use App\Models\UserFirebase;
use App\Models\ApprenantFirebase;

class ApprenantsPolicy
{
    public function view(UserFirebase $user, ApprenantFirebase $apprenants)
    {
    }

    public function update(UserFirebase $user, ApprenantFirebase $apprenants)
    {
    }

    public function delete(UserFirebase $user, ApprenantFirebase $apprenants)
    {
    }
}
