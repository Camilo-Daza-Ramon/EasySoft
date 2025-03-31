<?php

namespace App\Http\Controllers;

use App\DocumentalArchivo;
use App\DocumentalVersion;
use App\DocumentalProyecto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Storage;
use DB;

class DocumentalArchivoController extends Controller
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
    public function store(Request $request, DocumentalProyecto $documental_proyecto, $versione)
    {
        if (Auth::user()->can('documental-versiones-archivos-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'archivo' => 'required|mimes:jpg,jpeg,png,pdf,docx,xlsx,zip,rar|max:500000'
            ]);

            //Asignamos el nombre al documento
            $nombre = mb_convert_case(str_replace(' ', '_', $request->nombre), MB_CASE_LOWER, "UTF-8");

            //Declaramos una ruta
            $directory = 'proyectos/'.$documental_proyecto->proyecto_id.'/documental/'.$documental_proyecto->id.'/version/'.$versione;

            //Declaramos el documento
            $file = $request->file('archivo');
            //Si no existe el directorio, lo creamos
            if (!file_exists($directory)) {
                //Creamos el directorio
                Storage::makeDirectory($directory);
            }

            //Obtenemos el tipo de documento que se esta subiendo
            $extension = strtolower($request->file('archivo')->getClientOriginalExtension());

            //declaramos la ruta del documento
            $ruta = $directory.'/'.$nombre.'.'.$extension;

            //Indicamos que queremos guardar un nuevo documento en el directorio publico
            Storage::put($ruta, \File::get($file));

            $existe = Storage::exists($ruta);

            if ($existe) {

                $archivo =  new DocumentalArchivo;
                $archivo->nombre = ucwords(mb_convert_case($request->nombre, MB_CASE_LOWER, "UTF-8"));
                $archivo->ruta = $ruta;
                $archivo->tipo = $extension;
                $archivo->documental_version_id = $versione;

                if($archivo->save()){
                    return redirect()->route('documental-proyectos.show', $documental_proyecto->id)->with('success', 'Archivo cargado correctamente.');
                }else{
                    return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('error', 'Error al guardar el archivo.');
                }

            }else{
                return redirect()->route('documental-proyectos.show', $documental_proyecto->id)->with('error', 'Error al cargar el archivo.');
            }
        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DocumentalArchivo  $documentalArchivo
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentalArchivo $documentalArchivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentalArchivo  $documentalArchivo
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentalArchivo $documentalArchivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentalArchivo  $documentalArchivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentalArchivo $documentalArchivo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentalArchivo  $documentalArchivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($documental_proyecto, $versione, DocumentalArchivo $archivo)
    {
        if (Auth::user()->can('documental-versiones-archivos-eliminar')) {
            
            $result = DB::transaction(function () use($archivo) {
              
              if($archivo->delete()){

                Storage::delete($archivo->ruta);
                $existe = Storage::exists($archivo->ruta);
                
                if($existe){
                    DB::rollBack();
                    return ['error', 'Archivo no se pudo eliminar.']; 
                }else{
                    return ['success', 'Archivo eliminado correctamente!']; 
                }
                
              }else{
                DB::rollBack();
                return ['error', 'Error al eliminar el registro de la tabla'];
              }
            });

            return redirect()->route('documental-proyectos.show', $documental_proyecto)->with($result[0], $result[1]);

    
        }else{
            abort(403);
        }
    }
}
