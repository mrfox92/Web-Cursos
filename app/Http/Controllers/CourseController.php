<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Course;
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
}
