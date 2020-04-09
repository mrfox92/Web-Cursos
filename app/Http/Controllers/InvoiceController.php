<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    public function admin () {
        //  collection es una coleccion de datos (vacia en este caso) como las que nos devuelven los modelos, y que tendremos muchos metodos
        $invoices = new Collection;

        //  comprobamos que el usuario se ha suscrito a algun plan
        if ( auth()->user()->stripe_id ) {
            $invoices = auth()->user()->invoices(); //  obtenemos las facturas
        }

        return view('invoices.admin', compact('invoices'));
    }

    public function download (Request $request, $id) {
        return $request->user()->downloadInvoice($id, [
            "vendor"    =>  "Mi empresa",
            "product"   =>  __("Suscripción")
        ]);
    }
}
