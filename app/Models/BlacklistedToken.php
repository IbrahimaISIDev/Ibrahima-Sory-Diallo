<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistedToken extends Model
{
    use HasFactory;
    protected $table = 'blacklisted_tokens';
    protected $fillable = ['token', 'type'];
    public $timestamps = true;
    protected $primaryKey = 'id';
}