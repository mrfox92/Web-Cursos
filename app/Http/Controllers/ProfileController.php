<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\StrengthPassword;

class ProfileController extends Controller
{
    public function index () {
        $user = auth()->user()->load('SocialAccount');
        return view('profile.index', compact('user'));
    }

    public function update ( Request $request ) {
        //  Agregamos la validacion personalizada
        $this->validate( $request, [
            'password'  =>  ['confirmed', new StrengthPassword]
        ]);

        $user = auth()->user();
        $user->password = bcrypt( $request->get('password') );
        $user->save();

        return back()->with('message', [
            'class'     =>  'success',
            'message'   =>  __("Datos de usuario actualizados exitosamente")
        ]);
    }
}
