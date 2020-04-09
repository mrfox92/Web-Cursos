<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = ['user_id', 'title'];

    protected $appends = ['courses_formatted']; //  agregamos un nuevo atributo a nuestro modelo
    
    //  un estudiante puede estar inscrito en uno o muchos cursos y un curso puede tener uno o muchos estudiantes
    //  para este tipo de relaciones se establecen las relaciones en los dos modelos para que funcione
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

    //  un estudiante es un usuario
    public function user() {
        return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'email');
    }

    // obtener los cursos formateados
    //  pluck: indicamos que campos deseamos devolver
    //  implode: indicamos con que signo deseamos separar los valores
    public function getCoursesFormattedAttribute() {
        return $this->courses->pluck('name')->implode('<br />');
    }
}
