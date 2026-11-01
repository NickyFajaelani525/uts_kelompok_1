<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // VULNERABILITY: Mass Assignment — $fillable not defined, $guarded empty
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class); }
}
