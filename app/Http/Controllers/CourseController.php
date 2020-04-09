<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Course;
use App\Review;
use App\Mail\NewStudentInCourse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Requests\CourseRequest;
use App\Helpers\Helper; //  hacemos uso de nuestro Helper creado para manipulacion de guardado de imagenes

class CourseController extends Controller
{

    // public function show ( $slug ) {
        
    //     //  modelo utilizando with y querybuilder
    //     //  tanto with como load realizan la misma acción, load puede además cargar las relaciones
    //     $course = Course::with([
    //         'category'  =>  function ( $q ) {
    //             $q->select('id', 'name');
    //         },
    //         'goals' => function ($q) {
    //             $q->select('id', 'course_id', 'goal');
    //         },
    //         'level' =>  function ($q) {
    //             $q->select('id', 'name');
    //         },
    //         'requirements' =>   function ($q) {
    //             $q->select('id', 'course_id', 'requirement');
    //         },
    //         'reviews.user',
    //         'teacher'
    //     ])->withCount(['students', 'reviews'])->where('slug', $slug)->first();
        
    //     dd($course);
    // }

    public function show (Course $course) {
        
        //  modelo utilizando with y querybuilder
        //  tanto with como load realizan la misma acción, load puede además cargar las relaciones
        $course->load([
            'category'  =>  function ( $q ) {
                $q->select('id', 'name');
            },
            'goals' => function ($q) {
                $q->select('id', 'course_id', 'goal');
            },
            'level' =>  function ($q) {
                $q->select('id', 'name');
            },
            'requirements' =>   function ($q) {
                $q->select('id', 'course_id', 'requirement');
            },
            'reviews.user',
            'teacher'
        ])->get();


        //  traemos los cursos relacionados
        $related = $course->relatedCourses();
        //  dd($related);
        return view('courses.detail', compact('course', 'related'));
        
    }

    public function inscribe ( Course $course ) {
        //  realizamos prueba del correo a enviar
        //  return new NewStudentInCourse( $course, 'admin');
        //  agregamos el estudiante al curso
        $course->students()->attach( auth()->user()->student->id );
        //  hacemos el envio del correo
        Mail::to($course->teacher->user)->send( new NewStudentInCourse( $course, auth()->user()->name) );

        return back()->with('message', [
            'class'     =>  'success',
            'message'   =>  __("Te has inscrito correctamente al curso")  
        ]);
    }

    public function subscribed () {
        $courses = Course::whereHas('students', function($query) {
            $query->where('user_id', auth()->id());
        })->get();
        // $courses = auth()->user()->student->courses;

        return view('courses.subscribed', compact('courses'));
    }

    public function addReview ( Request $request ) {
        //  creamos la review
        Review::create([
            'user_id'   =>  auth()->id(),
            'course_id' =>  $request->get('course_id'),
            'rating'    =>  (int) $request->get('rating_input'),
            'comment'   =>  $request->get('message')
        ]);

        return back()->with('message', [
            'class'     =>  'success',
            'message'   =>  __("Muchas gracias por valorar el curso")
        ]);
    }

    public function create () {
        //  creamos una nueva instanacia de curso
        $course = new Course();
        $btnText = __("Enviar curso para revisión");

        return view('courses.form', compact('course', 'btnText'));
    }

    public function store( CourseRequest $course_request ) {

        $picture = Helper::uploadFile('picture', 'courses');    //  el metodo estatico del Helper recibe dos parametros la key y el path
        //  unimos el nombre de la imagen al course_request
        $course_request->merge(['picture' => $picture]);
        $course_request->merge(['teacher_id' => auth()->user()->teacher->id]);
        $course_request->merge(['status' => Course::PENDING]);
        //  insertamos en nuestra base de datos un nuevo curso
        Course::create($course_request->input());
        // Course::create($course_request->except('_token'));
        //  hacemos un return back, con un mensaje
        return back()->with('message', [
            'class'     =>  'success',
            'message'   =>  __("Curso enviado correctamente, recibirá un correo con cualquier información")
        ]);
    }

    public function edit( $slug ) {

        
        $course = Course::with(['requirements', 'goals'])->withCount(['requirements', 'goals'])
            ->whereSlug($slug)->first();

        $btnText = __("Actualizar curso");

        return view('courses.form', compact('course', 'btnText'));
    }

    public function update ( CourseRequest $course_request, Course $course ) {

        //  comprobamos si dentro de la request viene un objeto llamado picture
        if ( $course_request->hasFile('picture') ) {
            \Storage::delete('courses/'. $course->picture); //  eliminamos la imagen del curso actual
            //  subimos nuestra imagen actualizada
            $picture = Helper::uploadFile('picture', 'courses');
            $course_request->merge(['picture' => $picture]);  //  enviamos dentro de la request el nombre de la imagen subida
        }

        //  actualizamos todos los datos, el metodo fill es para rellenar la informacion

        $course->fill( $course_request->input() )->save();  //  actualizamos el curso a partir de la informacion modificada en el formulario

        return redirect()->route('courses.edit', ['slug' => $course->slug])->with('message', [
            'class'     =>  'success',
            'message'   =>  __("Curso actualizado con éxito")
        ]);

    }


    public function destroy ( Course $course ) {
       
        try {

            $course->delete();
            return back()->with('message', [
                'class'     =>  'success',
                'message'   =>  __("Curso eliminado correctamente")
            ]);
         
        } catch(\Exception $exception) {

            return back()->with('message', [
                'class'     =>  'danger',
                'message'   =>  __("Error eliminando el curso")
            ]);
        }
    }
}
