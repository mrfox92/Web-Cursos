<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Teacher;
use Illuminate\Support\Facades\DB;

class SolicitudeController extends Controller
{
    public function teacher () {

        $user = auth()->user();
        //  verificamos si el usuario no es profesor
        if ( ! $user->teacher ) {

            try {

                DB::beginTransaction();

                $user->role_id = Role::TEACHER;
                $user->save();  //  guardamos los cambios para la tabla users
                //  creamos el nuevo profesor
                Teacher::create([
                    'user_id'   =>  $user->id
                ]);

                $success = true;

            } catch ( \Exception $exception ) {
                DB::rollback();
                $success = $exception->getMessage();
            }

            if ( $success === true ) {
                //  efectuamos los cambios
                DB::commit();
                //  cerramos la sesión del usuario
                auth()->logout();
                //  abrimos la sesion del usuario usando su id
                auth()->loginUsingId( $user->id );

                return back()->with('message', [
                    'class' =>  'success',
                    'message'   =>  __("Felicidades, ya eres instructor en la plataforma")
                ]);
            }
        }

        return back()->with('message', [
            'class'     =>  'danger',
            'message'   =>  __("Algo ha fallado")  
        ]);
    }
}
