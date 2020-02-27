<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    //  un curso puede tener uno y solo un nivel de dificultad - Relacion uno a uno
    //   se utiliza hasOne ya que la clave foranea está en la tabla Course
    public function course() {
        return $this->hasOne(Course::class);
    }
}
