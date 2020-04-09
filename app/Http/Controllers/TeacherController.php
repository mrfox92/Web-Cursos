<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\User;
use App\Course;
use App\Mail\MessageToStudent;

class TeacherController extends Controller
{
    public function courses() {
        //obtener los cursos para un profesor
        //  si queremos acceder y listar los cursos eliminados, lo realizamos mediante el metodo ->withTrashed() el cual trae todos los 
        //  elementos, incluso los que estan con borrado logico en base de datos
        $courses = Course::withCount(['students'])->with('category', 'reviews')
            ->where('teacher_id', auth()->user()->teacher->id)->paginate(2);
        // dd($courses);
        return view('teachers.courses', compact('courses'));
    }

    public function students() {
        $students = Student::with('user', 'courses.reviews')
            ->whereHas('courses', function ($q) {
                $q->where('teacher_id', auth()->user()->teacher->id)->select('id', 'teacher_id', 'name')
                    ->withTrashed();//  traemos ademas los datos que estan con borrado logico
            })->get();
        
        $actions = 'students.datatables.actions';   //  columna de accion
        //  rawColumns: le decimos que la columna sera html, y que respete el formato
        return \DataTables::of($students)->addColumn('actions', $actions)->rawColumns(['actions', 'courses_formatted'])->make(true);
    }

    public function sendMessageToStudent ( Request $request ) {

        //  recuperamos la informacion enviada via ajax
        $info = $request->get('info');
        $data = [];
        parse_str($info, $data);    //  ahora enviamos información dentro de data
        $user = User::findOrFail($data['user_id']);    //  buscamos el usuario a partir de su id, si no lo encuentra abortará la petición

        try {
            \Mail::to($user)->send( new MessageToStudent( auth()->user()->name, $data['message']) );
            $success = true;
        } catch (\Exception $exception) {
            $success = false;
        }

        return response()->json(['res' => $success]);
    }
}
