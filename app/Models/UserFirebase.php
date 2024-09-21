<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\UserFirebaseInterface;

class UserFirebase extends FirebaseModel implements UserFirebaseInterface
{
   protected $table = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'email',
        'password',
        'telephone',
        'photo',
        'fonction',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
