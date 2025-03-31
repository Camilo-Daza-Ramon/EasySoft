<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\PqrArchivo;
use Storage;


class PqrsArchivosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$pqr)
    {
        if (Auth::user()->can('pqrs-archivos-crear')) {

            $this->validate($request, [
                'nombre_foto.*' => 'required',        
                'foto.*' => 'required|mimes:pdf,jpg,jpeg,png|max:6000'
            ]);

            //Declaramos una ruta
            $directory = 'pqrs/'.$pqr;

            foreach ($request->foto as $key => $file) {

                $validar = PqrArchivo::where([['Comentario', $request->nombre_foto[$key]], ['PqrId', $pqr]])->count();

                if ($validar == 0) {

                    //Si no existe el directorio, lo creamos
                    if (!file_exists($directory)) {
                        //Creamos el directorio
                        Storage::makeDirectory($directory);
                    }

                    //Asignamos el nombre al archivo
                    $nombre = mb_convert_case($request->nombre_foto[$key], MB_CASE_TITLE, "UTF-8");       

                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($file->getClientOriginalExtension());
                    

                    //declaramos la ruta del archivo
                    $ruta_archivo_soporte = $directory.'/'.$nombre.'.'.$extension;

                    //Indicamos que queremos guardar un nuevo archivo en el disco local
                    Storage::put($ruta_archivo_soporte, \File::get($file));

                    $existe = Storage::exists($ruta_archivo_soporte);

                    if ($existe) {
                        $archivo = new PqrArchivo;
                        $archivo->Comentario = mb_convert_case($nombre,MB_CASE_TITLE, "UTF-8");
                        $archivo->ruta = $ruta_archivo_soporte;
                        $archivo->tipo_archivo = $extension;
                        $archivo->PqrId = $pqr;
                        $archivo->Enlace = null;
                        //$archivo->Esfoto = ($extension != 'pdf')? 0 : 1;

                        if(!$archivo->save()){
                            return redirect()->route('pqr.show', $pqr)->with('error','Error al guardar el archivo ' .$request->nombre_foto[$key]);
                        }
                    }else{
                        return redirect()->route('pqr.show', $pqr)->with('error','Error al subir el archivo ' .$request->nombre_foto[$key]);
                    }
                }
            }

            return redirect()->route('pqr.show', $pqr)->with('success','Archivos cargados satisfactoriamente!');      

        }else{
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pqr,$id)
    {
        if (Auth::user()->can('pqrs-archivos-eliminar')) {
            $archivo = PqrArchivo::where([['PqrId',$pqr],['PqrArcId',$id]])->first();

            $validar = PqrArchivo::where([['PqrId',$pqr],['Enlace',$archivo->Enlace]])->count();

            $eliminar_archivo = false;

            if ($validar < 2) {
                $eliminar_archivo = true;
            }

            if ($eliminar_archivo) {
                if (Storage::exists($archivo->ruta)){
                    Storage::delete($archivo->ruta);
                }
            }

            if ($archivo->delete()) {
                return redirect()->route('pqr.show', $pqr)->with('success','Archivo eliminado correctamente.');
            }else{
                return redirect()->route('pqr.show', $pqr)->with('danger','No se pudo eliminar el archivo.');
            }
        }else{
            abort(403);
        }
    }
}
