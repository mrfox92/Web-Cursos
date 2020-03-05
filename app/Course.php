<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    //  reconstruimos la ruta de las imagenes
    public function pathAttachment () {
        return "images/courses/" . $this->picture;
    }

    //  un curso solo puede tener una categoria - relación uno a uno
    public function category () {
        return $this->belongsTo(Category::class)->select('id', 'name');
    }

    //  un curso puede tener una o muchas metas - relación uno a muchos

    public function goals () {
        return $this->hasMany(Goal::class)->select('id', 'course_id', 'goal');
    }

    //  un curso solo puede tener un nivel - relacion uno a uno
    //  Se utiliza belongsTo ya que la clave foranea está en la tabla Course
    public function level () {
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    //  un curso puede tener una o muchas valoraciones - relación uno a muchos
    public function reviews () {
        return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment');
    }

    //  un curso puede tener uno o muchos requisitos - relación uno a muchos
    public function requirements () {
        return $this->hasMany(Requirement::class)->select('id', 'course_id', 'requirement');
    }

    //  un curso puede tener uno o muchos estudiantes y un estudiante puede estar inscrito en uno o muchos cursos - Relación muchos a muchos
    //  para este tipo de relaciones se establecen estas relaciones desde los dos modelos
    public function students () {
        return $this->belongsToMany(Student::class);
    }

    //  Un curso puede tener un profesor y un profesor puede tener uno o muchos cursos - relación uno a muchos

    public function teacher () {
        return $this->belongsTo(Teacher::class);
    }

    // Los métodos de los modelos de Eloquent que inician con get y 
    // finalizan con Attribute son automáticamente reconocidos y pasan
    // a ser datos del mismo modelo en la obtención.
    
    //  retornamos el promedio de las valoraciones de los cursos

    public function getCustomRatingAttribute() {
        return $this->reviews->avg('rating');
    }
}
