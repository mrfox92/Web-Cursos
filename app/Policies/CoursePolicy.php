<?php

namespace App\Policies;

use App\Course;
use App\User;
use App\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    //  metodo para verificar si un usuario puede o no optar a un curso
    public function opt_for_course (User $user, Course $course) {

        return ! $user->teacher || $user->teacher->id !== $course->teacher_id;
    }

    //  Comprobamos si el usuario puede suscribirse, siempre y cuando no sea usuario Adminstrador
    //  y que si el usuario no tiene algun plan contratado
    public function subscribe (User $user) {

        return $user->role_id !== Role::ADMIN && ! $user->subscribed('main');
    }

    //  verificamos si el usuario puede inscribirse en el curso seleccionado
    //  la funcion contains, comprueba si entre la relación muchos a muchos (entre cursos y estudiantes) existe un estudiante
    //  cuyo id este presente en el curso seleccionado, de ser asi no puede inscribirse(ya inscrito), caso contrario puede inscribirse
    public function inscribe (User $user, Course $course) {
        return ! $course->students->contains($user->student->id);
    }

    //  comprobamos si el usuario puede añadir una revisión
    public function review (User $user, Course $course) {
        return ! $course->reviews->contains('user_id', $user->id);
    }

}
