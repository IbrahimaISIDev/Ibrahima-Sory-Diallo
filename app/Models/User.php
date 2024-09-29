<?php
namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;
    
    protected $connection = 'mysql';
    protected $roles = [];
    protected $fillable = [
        'nom', 
        'prenom', 
        'adresse', 
        'email', 
        'password', 
        'telephone', 
        'photo', 
        'role_id', 
        'fonction', 
        'status'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMysql::class);
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }   
}