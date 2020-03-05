<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $courses = \App\Course::withCount(['students']) //  realizamos el conteo de los estudiantes del Modelo Course a traves del metodo students
            ->with('category', 'teacher', 'reviews')    //  traemos la categoria, el profesor y las reviews relacionadas a partir de los metodos del modelo Course
            ->where('status', \App\Course::PUBLISHED)   //  Seleccionamos el estado
            ->latest()  //  traemos ordenados desde el mas reciente
            ->paginate(12); //  paginamos los resultados de 12 en 12
        return view('home', compact('courses'));
    }
}
