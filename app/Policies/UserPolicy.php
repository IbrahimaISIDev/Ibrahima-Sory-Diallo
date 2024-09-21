<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user, string $role)
    {
        if ($user->role === 'Admin') {
            return in_array($role, ['Admin', 'Coach', 'Manager', 'CM']);
        } elseif ($user->role === 'Manager') {
            return in_array($role, ['Coach', 'Manager', 'CM']);
        }
        return false;
    }

    public function createLearner(User $user)
    {
        return in_array($user->role, ['Admin', 'Manager', 'CM']);
    }

    public function createLearnerList(User $user)
    {
        return in_array($user->role, ['Admin', 'Manager', 'CM']);
    }
}