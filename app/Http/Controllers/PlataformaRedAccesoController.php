<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PlataformaRedAcceso;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PlataformaRedAccesoController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (Auth::user()->can('gestion-red-crear')) {
                $this->validate($request, [
                    'usuario' => 'required|string',
                    'contrasena' => 'required|string',
                ]);
                $acceso = new PlataformaRedAcceso();
                $acceso->usuario = $request->get('usuario');
                $acceso->contrasena = Crypt::encrypt($request->get('contrasena'));
                if ($acceso->save()) {
                    return $acceso->id;
                }
                return false;
            }
            abort(403);
        } catch (\Throwable $th) {
        
            dd($th->getMessage());
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('gestion-red-eliminar')) {
            $dato_acceso = PlataformaRedAcceso::findOrFail($id);
            if ($dato_acceso->plataformas_de_red->count() < 1) {
                $dato_acceso->delete();
            }
            return;
        }

        abort(403);
    }
}
