<?php
namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserMysql extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    protected $connection = 'mysql';
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
    protected $table = 'users';
}