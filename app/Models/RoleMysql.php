<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMysql extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'libelle', 
    ];
    protected $table = 'roles';

    public function users()
    {
        return $this->hasMany(UserMysql::class);
    }
}