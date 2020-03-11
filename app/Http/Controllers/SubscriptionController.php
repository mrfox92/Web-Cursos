<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //  definimos nuestro middleware
    public function __construct() {

        $this->middleware( function ($request, $next) {
            //  comprobamos si el usuario esta suscrito a algún plan
            if ( auth()->user()->subscribed('main') ) {
                //  redirigimos hacia la raiz de la aplicacion
                return redirect('/')
                    ->with('message', [
                        'class'     =>  'warning', 
                        'message'   =>  __("Actualmente estás suscrito a otro plan")
                        ]);
            }
            //  sino esta suscrito lo dejamos continuar con el proceso de suscripcion
            return $next($request);
        })->only('plans', 'processSubscription');
    }

    public function plans () {
        return view('subscriptions.plans');
    }

    public function processSubscription ( Request $request ) {
        //  $token = $request->get('stripeToken');    //  recibimos el token de stripe enviado via request para validar y comprobar
        $plan = $request->get('type');
        $cupon = $request->get('coupon');
        try {
            //  verificamos si viene la llave coupon en la request
            //  se debe comprobar si viene la llave cupon y además si no viene vacia
            if ( $request->has('coupon') && !empty( $cupon ) ) {

                //  realizamos una nueva suscripcion utilizando el cupon ingresado por el usuario
                $request->user()->newSubscription('main', $plan )
                    ->withCoupon($cupon)->create($request->stripeToken);

            } else {
                //  caso contrario, de no existir un cupon, realizamos la suscripcion sin cupon
                $request->user()->newSubscription('main', $plan )
                    ->create($request->stripeToken);
            }

            //  redireccionamos al usuario
            return redirect( route('subscriptions.admin') )
                ->with('message', [
                    'class' =>  'success',
                    'message'   =>  __("La suscripción se ha realizado con éxito")
                ]);
        }
        catch ( \Exception $exception ) {

            $error = $exception->getMessage();
            return back()->with('message', [
                'class'     =>  'danger',
                'message'   =>  $error  
            ]);
        }
    }


    public function admin () {
        return view('subscriptions.admin');
    }
}
