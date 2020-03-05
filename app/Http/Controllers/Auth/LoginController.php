<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use App\Student;
use App\UserSocialAccount;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //  sobreescribimos el metodo logout
    public function logout( Request $request ) {

        //  cerramos la sesion
        auth()->logout();
        //  forzamos el borrado de la sesion
        session()->flush();
        //  redirigimos
        return redirect('login');
    }

    public function redirectToProvider(string $driver) {
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback(string $driver) {

        if ( !request()->has('code') ) {

            session()->flash('message', [
                'class'     => 'danger',
                'message'   => __("Inicio de sesión cancelado")   
            ]);

            return redirect('login');
    
        }

        $socialUser = Socialite::driver($driver)->user();
        //  dd($socialUser);

        $user = null;
        $success = true;
        $email = $socialUser->email;
        $check = User::whereEmail($email)->first(); //  comprobamos si el usuario existe en la base de datos a partir de su correo
        if ( $check ) {
            $user = $check;
        } else {
            
            DB::beginTransaction(); //  inicio transaccion
            try {
                $user = User::create([
                    'name'  =>  $socialUser->name,
                    'email' =>  $email
                ]); //  nos devuelve la instancia del modelo

                UserSocialAccount::create([
                    'user_id'       =>  $user->id,
                    'provider'      =>  $driver,
                    'provider_uid'  =>  $socialUser->id
                ]);

                //  registramos el estudiante
                Student::create([
                    'user_id'   =>  $user->id
                ]);

            } catch ( \Exception $exception ) {
                $success = $exception->getMessage();
                DB::rollback();
            }
            
        }

        //  verificamos si todo va bien
        if( $success === true ) {
            
            DB::commit();
            auth()->loginUsingId($user->id);    //  iniciamos sesion de nuestro usuario
            return redirect( route('home') );
        }

        session()->flash('message', [
            'class'     =>  'danger',
            'message'   =>  $success
        ]);
        return redirect('login');
    }
}
