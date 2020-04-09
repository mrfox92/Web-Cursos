<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        //  casteamos el valor de role, ya que viene como un string y necesitamos utilizarlo como un entero
        if ( auth()->user()->role_id !== (int) $role ) {
            abort(401, __("No puedes acceder a esta zona") );
        }
        return $next($request);
    }
}
