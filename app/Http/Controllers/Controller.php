<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function setLanguage ( $language ) {

        //  comprobamos que la llave en el array languages
        if ( array_key_exists( $language, config('languages') ) ) {

            session()->put('appLocale', $language); //  enviamos a la session el elemento language
        }

        return back();
    }
}
