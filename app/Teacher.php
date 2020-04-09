<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id'];
    //  un profesor puede tener uno o muchos curso - Relación uno a muchos
    public function courses () {
        return $this->hasMany(Course::class);
    }

    //  un profesor tambien es un usuario
    public function user () {
        return $this->belongsTo(User::class);
    }
}
