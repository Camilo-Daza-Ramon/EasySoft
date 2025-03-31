<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PlataformaRedInstruccion;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlataformaRedInstruccionController extends Controller
{
    public function store(Request $request, $plataforma_id)
    {

        if (Auth::user()->can('gestion-red-crear')) {
            $this->validate($request, [
                'archivo-instrucciones' => 'required|file|mimes:pdf',
            ]);

            $file = $request->file('archivo-instrucciones');
            $nombreArchivo = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $instruccion = new PlataformaRedInstruccion();
            $instruccion->nombre = $nombreArchivo;
            $instruccion->tipo = $extension;


            $directory = 'red/plataforma/'.$plataforma_id;
            $ruta = $directory . '/' . $nombreArchivo;
            Storage::put($ruta, \File::get($file));
            $instruccion->ruta = $ruta;

            return $instruccion->save() ? $instruccion->id : false;
        }

        return false;
    }

    public function destroy($id)
    {
        if (Auth::user()->can('gestion-red-eliminar')) {
            $instruccion = PlataformaRedInstruccion::findOrFail($id);
            if ($instruccion->plataformas_de_red->count() < 1) {
                if (Storage::exists($instruccion->ruta)) {
                    $explodeFile =  explode('/', $instruccion->ruta);
                    $nameFile = $explodeFile[count($explodeFile) - 1];
                    $routeDir = str_replace($nameFile, '', $instruccion->ruta);
                    Storage::deleteDirectory($routeDir);
                    $instruccion->delete();
                }
            }
            return;
        }
        abort(403);
    }
}
