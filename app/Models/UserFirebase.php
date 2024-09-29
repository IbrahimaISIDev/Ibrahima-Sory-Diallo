<?php

namespace App\Models;

use App\Observers\UserObserver;
use App\Interfaces\UserFirebaseInterface;

class UserFirebase extends FirebaseModel implements UserFirebaseInterface
{
    protected $path = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'email',
        'password',
        'telephone',
        'photo',
        'fonction',
        'role_id',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
}
