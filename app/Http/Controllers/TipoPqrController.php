<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TipoPqr;



class TipoPqrController extends Controller
{
    public function ajax(Request $request){

        $clasificaciones = [];

        if(!empty($request)){
            $clasificaciones = TipoPqr::select('TipologiaPqr as id', 'Descripcion as descripcion')->where('ClasificacionPqr', $request->tipo_pqr)->orderBy('Descripcion', 'ASC')->get();
        }

        return response()->json(['clasificaciones' => $clasificaciones]);
    }
}
