<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\ClienteContrato;
use App\Cliente;
use App\Instalacion;
use App\MantenimientoArchivo;
use App\PlataformaDeRed;

class PrivateController extends Controller
{
    public function archivos_pqrs($pqr,$file){
      if (Auth::user()->can('pqrs-archivos-ver')) {

        $path = "pqrs/{$pqr}/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }


    public function archivos_campanas($campana,$cliente,$file){
      if (Auth::user()->can('campañas-respuestas-ver')) {

        $path = "campanas/{$campana}/{$cliente}/{$file}";

        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }

    public function documentos_proyectos($proyecto,$file){
      if (Auth::user()->can('proyectos-documentos-ver')) {

        $path = "proyectos/{$proyecto}/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }

    public function documental_proyectos($proyecto,$documental,$version,$file){
      if (Auth::user()->can('proyectos-documentos-ver')) {

        $path = "proyectos/{$proyecto}/documental/$documental/version/$version/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }


    public function archivos_contratos($id,$file){
      if (Auth::user()->can('contratos-archivos-ver')) {

        $cliente_contrato = ClienteContrato::findOrFail($id);

        if(Auth::user()->proyectos()->count() > 0){

          $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

          if(!in_array($cliente_contrato->cliente->ProyectoId, $array)){
            abort(403);
          }
        }

        $path = "contratos/{$id}/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }

    public function archivos_clientes($id,$file){
      if (Auth::user()->can('clientes-archivos-ver')) {

        $cliente = Cliente::where('Identificacion', $id)->first();

        if(Auth::user()->proyectos()->count() > 0){

          $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

          if(!in_array($cliente->ProyectoId, $array)){
            abort(403);
          }

        }

        $path = "clientes/{$id}/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }

    public function archivos_instalaciones($id,$file){
      if (Auth::user()->can('instalaciones-ver')) {

        $instalacion = Instalacion::findOrFail($id);

        if(Auth::user()->proyectos()->count() > 0){

          $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

          if(!in_array($instalacion->cliente->ProyectoId, $array)){
            abort(403);
          }
        }

        $path = "installations/{$id}/{$file}";
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
      }else{
        abort(403);
      }
    }

    public function archivos_instrucciones($id, $file) {
        if (!Auth::user()->can('gestion-red-listar')) {
          abort(403);
        }

        $plataforma = PlataformaDeRed::find($id);
        $path = $plataforma->instruccion->ruta;
        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);
    }


    public function archivos_mantenimientos($tipo, $id, $file) {
      if (Auth::user()->can('mantenimientos-archivos-ver')) {

        $path = "mantenimientos/{$tipo}/{$id}/{$file}";

        if (!Storage::disk('local')->exists($path)) {
          abort(404);
        }

        $local_path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;
        return response()->file($local_path);

      }else{
        abort(403);
      }
    }


    
}
