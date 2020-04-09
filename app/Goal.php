<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = ['course_id', 'goal'];
    //  un curso puede tener una o muchas metas y una o muchas metas pertenecen a un curso - Relación uno a muchos

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
