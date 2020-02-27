<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    //  Un requisito pertenece a un curso y un curso puede tener uno a muchos requisitos - Relación de uno a muchos

    public function course () {
        return $this->belongsTo(Course::class);
    }
}
