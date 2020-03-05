<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( session('appLocale') ) {
            
            $configLanguage = config('languages')[session('appLocale')];
            setLocale(LC_TIME, $configLanguage[1] . '.utf8');
            //  las fechas las formateamos de acuerdo al formato del idioma
            Carbon::setLocale( session('appLocale') );
            //  Establecemos el idioma de nuestra aplicacion
            App::setLocale( session('appLocale') );
            
        } else {
            
            session()->put('appLocale', config('app.fallback_locale'));
            setlocale(LC_TIME, 'es_ES.utf8');
            //  las fechas las formateamos de acuerdo al formato del idioma
            Carbon::setLocale( session('appLocale') );
            //  Establecemos el idioma de nuestra aplicacion
            App::setLocale( config('app.fallback_locale') );
            
        }

        return $next($request);
    }
}
