<?php

namespace App\Models;

use App\Observers\UserObserver;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class UserMongodb extends Model implements Authenticatable
{
    use AuthenticatableTrait, HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'users';

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

    protected static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);
    }
}