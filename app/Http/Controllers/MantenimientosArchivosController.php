<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoArchivo;
use App\Cliente;
use App\Mantenimiento;
use Storage;

class MantenimientosArchivosController extends Controller
{

    public function show()
    {
        if (Auth::user()->can('mantenimientos-archivos-ver')) {

        }else{
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $mantenimiento
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $mantenimiento)
    {
        
        if (Auth::user()->can('mantenimientos-archivos-crear')) {

            $this->validate($request, [
                'nombre_foto.*' => 'required',        
                'foto.*.file' => 'required|mimes:pdf,jpg,jpeg,png|max:6000',
                'mantenimiento_tipo' => 'required'
            ]);

            $directory = null;
            $link = null;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $directory = 'mantenimientos/preventivos/'.$mantenimiento;
                $link = "preventivos.show";
            }else{
                $directory = 'mantenimientos/correctivos/'.$mantenimiento;
                $link = "correctivos.show";

            }

            $link = (!empty($request->link)? $request->link : $link);

            foreach ($request->foto as $key => $file) {

                $validar = MantenimientoArchivo::
                where(function($query) use($request, $mantenimiento){
                    if($request->mantenimiento_tipo == 'PREVENTIVO'){
                        $query->where('mantenimiento_preventivo_id', $mantenimiento);
                    }else{
                        $query->where('mantenimiento_id', $mantenimiento);
                    }
                })
                ->where('nombre', $request->nombre_foto[$key])
                ->count();

                if ($validar == 0) {
                    $resultado = $this->addArchivo($mantenimiento, $directory, $request->nombre_foto[$key], $file, $request->mantenimiento_tipo);

                    if (!$resultado) {
                         return redirect()->route($link,$mantenimiento)->with('error','Error al subir el archivo ' .$request->nombre_foto[$key]);
                    }
                }                
            }

            return redirect()->route($link,$mantenimiento)->with('success','Archivos cargados satisfactoriamente!');

        }else{
            abort(403);
        }
    }

    public function update(Request $request, $mantenimiento, $archivo)
    {
        if (Auth::user()->can('mantenimientos-archivos-editar')) {
            
            $this->validate($request, [
                'nombre_foto' => 'required',        
                'foto' => 'required|mimes:pdf,jpg,jpeg,png|max:6000',
                'mantenimiento_tipo' => 'required'
            ]);

            $foto_archivo = MantenimientoArchivo::findOrFail($archivo);

            if (Storage::exists($foto_archivo->archivo)){
                Storage::delete($foto_archivo->archivo);
            }

            $directory = null;
            $link = null;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $directory = 'mantenimientos/preventivos/'.$mantenimiento;
                $link = "preventivos.show";
            }else{
                $directory = 'mantenimientos/correctivos/'.$mantenimiento;
                $link = "correctivos.show";
            }

            $link = (!empty($request->link)? $request->link : $link);
            

            $extension = strtolower($request->foto->getClientOriginalExtension());

            Storage::put($directory . '/' . $request->nombre_foto.'.'.$extension, \File::get($request->foto));

            $documento = $directory.'/'.$request->nombre_foto.'.'.$extension;

            $foto_archivo->tipo_archivo = $extension;
            $foto_archivo->archivo = $documento;

            if($foto_archivo->update()){
                return redirect()->route($link,$mantenimiento)->with('success','Archivo actualizado correctamente.');
            }else{
                return redirect()->route($link,$mantenimiento)->with('error','Error al actualizar el archivo.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $mantenimiento
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($mantenimiento,$id)
    {
        if (Auth::user()->can('mantenimientos-archivos-eliminar')) {

            $foto_archivo = MantenimientoArchivo::findOrFail($id);

            $link = null;
    
            if(!empty($foto_archivo->mantenimiento_preventivo_id)){
                $link= "preventivos.show";
            }else{
                $link= "correctivos.show";
            }

            $link = (!empty($request->link)? $request->link : $link);

            $estado = (!empty($foto_archivo->mantenimiento_preventivo_id))? $foto_archivo->mantenimiento_preventivo->estado : $foto_archivo->mantenimiento->estado;

            if($estado == 'CERRADO'){
                return redirect()->route($link, $mantenimiento)->with('warning','Ya no se permite eliminar evidencias.');

            }else{

                if(Storage::exists($foto_archivo->archivo)){
                    Storage::delete($foto_archivo->archivo);
                }
    
                if($foto_archivo->delete()) {    
                    return redirect()->route($link, $mantenimiento)->with('success','Archivo eliminado');
                }else{
                    return redirect()->route($link, $mantenimiento)->with('error','No se pudo eliminar.');
                }

            }
            
        }else{
            abort(403);
        }
    }

    private function addArchivo($mantenimiento, $directory, $nombre, $file, $tipo){

        $extension = strtolower($file->getClientOriginalExtension());
        $archivo = "";
        $documento = ""; 


        $documento = $directory.'/'.$nombre.'.'.$extension;
        

        //Si no existe el directorio, lo creamos
        if (!file_exists($directory)) {
            //Creamos el directorio
            Storage::makeDirectory($directory);
        }


        //Indicamos que queremos guardar un nuevo archivo en el disco local
        Storage::put($directory . '/' . $nombre.'.'.$extension, \File::get($file));

        //if ($extension != 'pdf') {
            //Storage::disk('public')->put($documento, $file);  
        //}else{
            //Storage::disk('public')->put($documento, \File::get($file));
        //}

        $existe = Storage::exists($documento);
        if ($existe) {
            $archivo = new MantenimientoArchivo;
            $archivo->nombre = mb_convert_case(str_replace('_', ' ', $nombre), MB_CASE_TITLE, "UTF-8");
            $archivo->archivo = $documento;
            $archivo->tipo_archivo = $extension;

            if($tipo == 'PREVENTIVO'){
                $archivo->mantenimiento_preventivo_id = $mantenimiento;
            }else{
                $archivo->mantenimiento_id = $mantenimiento;
            }

            return $archivo->save();
        }
    }
}
