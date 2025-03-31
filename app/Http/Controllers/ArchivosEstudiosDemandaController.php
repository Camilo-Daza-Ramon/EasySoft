<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArchivoEstudioDemanda;
use Storage;

class ArchivosEstudiosDemandaController extends Controller
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
    public function store(Request $request)
    {
        $this->validate(request(),[
            'nombre' => 'required',
            'estudio_id' => 'required',
            'archivo' => 'required|mimes:pdf,xlsx,docx,jpg,jpeg,png|max:10000'
        ]);

        $nombre = strtolower(utf8_decode(str_replace(" ", "_",$request->nombre)));

        $directory = 'estudios-demanda/'.$request->estudio_id;

        $file = $request->archivo;

        //Si no existe el directorio, lo creamos
        if (!Storage::disk('public')->exists($directory)) {
            //Creamos el directorio
            Storage::makeDirectory('public/' . $directory);
        }

        //Obtenemos el tipo de archivo que se esta subiendo
        $extension = strtolower($file->getClientOriginalExtension());

        //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
        $documento = $directory.'/'.$nombre.'.'.$extension;        

        if (!Storage::disk('public')->exists($documento)) {
            
            //Indicamos que queremos guardar un nuevo archivo en el disco local
            //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));
            Storage::disk('public')->put($documento, \File::get($file));

            $archivo = new ArchivoEstudioDemanda;
            $archivo->nombre = $request->nombre;
            $archivo->archivo = $documento;
            $archivo->tipo = $extension;
            $archivo->estudio_demanda_id = $request->estudio_id;

            if ($archivo->save()) {
                return redirect()->route('estudios-demanda.show', $request->estudio_id)->with('success','Archivo agregado satisfactoriamente.');
            }else{
                return redirect()->route('estudios-demanda.show', $request->estudio_id)->with('error','No se pudo guardar el archivo.');
            }
        }else{
            return redirect()->route('estudios-demanda.show', $request->estudio_id)->with('warning','Ya existe otro archivo con el mismo nombre.');
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
    public function destroy($id)
    {

        $archivo = ArchivoEstudioDemanda::findOrFail($id);

        $estudio = $archivo->estudio_demanda_id;

        if ($archivo->delete()) {
            //Eliminamos el archivo existente
            if (Storage::disk('public')->exists($archivo->archivo)){
                Storage::disk('public')->delete($archivo->archivo);
            }

            return redirect()->route('estudios-demanda.show', $estudio)->with('success','Archivo eliminado con exíto!');
        }else{
            return redirect()->route('estudios-demanda.show', $estudio)->with('error','No se pudo eliminar el archivo.');
        }
    }
}
