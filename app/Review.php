<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    //  definimos los campos a insertar, con esto evitamos problemas de asignación masiva
    protected $fillable  = ['course_id', 'user_id', 'rating', 'comment'];
    //  un curso puede tener una o muchas reviews
    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
