<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Requirement;
use App\Goal;

class Course extends Model
{
    use SoftDeletes;    //para borrado logico de datos

    protected $fillable = ['teacher_id', 'name', 'description', 'picture', 'level_id', 'category_id', 'status'];
    const PUBLISHED = 1;
    const PENDING = 2;
    const REJECTED = 3;

    //  realizamos el conteo de las reviews y students de un curso
    protected $withCount = ['reviews', 'students'];

    //  metodo estático
    public static function boot() {
        parent::boot(); //  ejecuta el metodo boot de la clase padre

        //  añadimos el slug

        static::saving(function( Course $course ){

            if ( ! \App::runningInConsole() ) {
                $course->slug = Str::slug($course->name, '-');
            }
        });

        //  comprobamos al momento que ha sido guardado
        //  con todo esto, cada vez que se actualiza o se crea una meta o un requisito se actualizara o guardará en base de datos con la
        //  respectiva relacion con el curso al que corresponde
        static::saved(function(Course $course) {
            //  comprobamos si no estamos corriendo desde la consola
            if ( ! \App::runningInConsole() ) {
                //  comprobamos si vienen requisito
                if ( request('requirements') ) {
                    //  recorremos los requisito
                    foreach ( request('requirements') as $key => $requirement_input) {
                        //  comprobamos si el requerimiento existe
                        if ($requirement_input) {
                            //  entonces lo creamos o lo actualizamos en la tabla Requirement
                            Requirement::updateOrCreate(['id' => request('requirement_id'. $key)], [
                                'course_id'     =>  $course->id,
                                'requirement'   =>  $requirement_input  
                            ]);
                        }
                    }
                }
            }

            //  realizamos la misma comprobacion para las metas del curso
            //  verificamos si no estamos corriendo desde la consola, ej: cuando realizamos las migraciones con las factories y seeds
            if( ! \App::runningInConsole() ) {
                //  comprobamos si hay metas
                if ( request('goals') ) {
                    //  si las hay entonces las recorremos
                    foreach ( request('goals') as $key => $goal_input) {
                        //  comprobamos si existe el elemento goal_input
                        if ( $goal_input ) {
                            Goal::updateOrCreate(['id' => request('goal_id'. $key)], [
                                'course_id'     =>  $course->id,
                                'goal'          =>  $goal_input
                            ]);
                        }
                    }
                }
            }


        });
    }

    //  reconstruimos la ruta de las imagenes
    public function pathAttachment () {
        return "/images/courses/" . $this->picture;
    }

    //  retornamos la llave desde la cual queremos devolver el registro de un curso
    public function getRouteKeyName() {
        return 'slug';
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
        return $this->hasMany(Review::class)->select('id', 'user_id', 'course_id', 'rating', 'comment', 'created_at');
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
    
    //  Retornamos el promedio de las valoraciones de los cursos

    public function getCustomRatingAttribute() {
        return $this->reviews->avg('rating');
    }

    //  Devolvemos los cursos relacionados que tengan la misma categoria que la del curso que accesa el metodo. 
    //  Y además el id del curso sea distinto del que accesa al metodo
    public function relatedCourses () {
        return Course::with('reviews')->whereCategoryId($this->category->id)
            ->where('id', '!=', $this->id)
            ->latest()
            ->limit(6)
            ->get();
    }
}
