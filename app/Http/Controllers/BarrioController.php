<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarrioController extends Controller
{
    public function ajax(Request $request){

        if ($request->ajax()) {

            $barrios = Cliente::select('Barrio')
            ->join('campanas_clientes as cc', 'Clientes.ClienteId', '=', 'cc.cliente_id')
            ->where([
                ['municipio_id', $request->municipio],
                ['cc.campana_id', $request->campana_id],
            ])
            ->groupBy('Barrio')
            ->orderBy('Barrio', 'ASC')
            ->get();

            return response()->json($barrios);
        }
    }
}
