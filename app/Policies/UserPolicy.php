<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserMysql;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->fonction === 'ADMIN' || ($user->fonction === 'MANAGER');
    }

    public function view(User $user, User $model)
    {
        return $user->fonction === 'ADMIN' || ($user->fonction === 'MANAGER' && $model->fonction !== 'ADMIN');
    }

    public function create(User $user)
    {
        $fonction = $user->fonction;
        return $this->canCreateUser($user, $fonction);
    }

    public function canCreateUser(User $user, string $fonction)
    {
        if ($user->fonction === 'ADMIN') {
            return in_array($fonction, ['ADMIN', 'Coach', 'MANAGER', 'CM']);
        } elseif ($user->fonction === 'MANAGER') {
            return in_array($fonction, ['Coach', 'MANAGER', 'CM']);
        }
        return false;
    }

    public function update(User $user, User $model)
    {
        return $user->fonction === 'ADMIN' || ($user->fonction === 'MANAGER' && $model->fonction !== 'ADMIN');
    }

    public function delete(User $user)
    {
        return $user->fonction === 'ADMIN';
    }
}