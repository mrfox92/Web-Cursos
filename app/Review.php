<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //  un curso puede tener una o muchas reviews
    public function course () {
        return $this->belongsTo(Course::class);
    }
}
