<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // VULNERABILITY: Mass Assignment — fillable not restricted
    protected $fillable = [];

    public function posts() { return $this->hasMany(Post::class); }
}
