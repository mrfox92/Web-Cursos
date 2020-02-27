<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    
    //  un estudiante puede estar inscrito en uno o muchos cursos y un curso puede tener uno o muchos estudiantes
    //  para este tipo de relaciones se establecen las relaciones en los dos modelos para que funcione
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    //  un estudiante es un usuario
    public function user () {
        return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'email');
    }
}
