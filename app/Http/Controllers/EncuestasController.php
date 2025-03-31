<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

use App\EncuestaSatisfaccion;


class EncuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('encuestas-listar')) {
            $encuestas = EncuestaSatisfaccion::Buscar($request->get('palabra'))->paginate(15);
            return view('adminlte::atencion-clientes.encuestas.index', compact('encuestas'));
        }else{
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('encuestas-crear')) {
            $this->validate(request(),[
                'descripcion' => 'required',            
                'respuestas' => 'required',
                'estado' => 'required']
            );

            $encuesta = new EncuestaSatisfaccion;
            $encuesta->descripcion = $request->descripcion;
            $encuesta->respuesta = json_encode(explode(",",$request->respuestas));
            $encuesta->estado = $request->estado;

            if ($encuesta->save()) {

                if (!empty($request->archivo)) {

                    $this->validate(request(),[
                        'archivo' => 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav|max:5000'
                    ]);

                    //Declaramos una ruta
                    $directory = 'encuestas/'.$encuesta->id;
                    $file = $request->archivo;

                    //Si no existe el directorio, lo creamos
                    if (!file_exists($directory)) {
                        //Creamos el directorio
                        Storage::makeDirectory('public/'.$directory);
                    }

                    //Obtenemos el tipo de archivo que se esta subiendo
                    $extension = strtolower($file->getClientOriginalExtension());
                    $nombre = 'audio_'.$encuesta->id;

                    $audio = $directory.'/'.$nombre.'.'.$extension;

                    Storage::disk('public')->put($audio, \File::get($file));

                    $existe = Storage::disk('public')->exists($audio);

                    if ($existe) {
                        $encuesta->archivo = $audio;
                        $encuesta->save();
                    }
                    
                }

                return redirect()->route('encuestas.index')->with('success', 'Registro agregado con exito!');
            }else{
                return $redirect()->route('error', 'Error al registrar la pregunta.');
            }
        }else{
            abort(403);
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
        if (Auth::user()->can('encuestas-actualizar')) {
            $encuesta = EncuestaSatisfaccion::findOrFail($id);
            $estados = array('ACTIVA', 'INACTIVA');

            return view('adminlte::atencion-clientes.encuestas.edit', compact('encuesta', 'estados'));

        }else{
            abort(403);
        }
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
        if (Auth::user()->can('encuestas-crear')) {
            $this->validate(request(),[
                'descripcion' => 'required',            
                'respuestas' => 'required',
                'estado' => 'required']
            );

            $encuesta = EncuestaSatisfaccion::find($id);
            $encuesta->descripcion = $request->descripcion;
            $encuesta->respuesta = json_encode(explode(",",$request->respuestas));
            $encuesta->estado = $request->estado;

            if ($request->archivo) {

                $this->validate(request(),[
                    'archivo' => 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav|max:5000'
                ]);

                if (Storage::disk('public')->exists($encuesta->archivo)){
                    Storage::disk('public')->delete($encuesta->archivo);
                }

                //Declaramos una ruta
                $directory = 'encuestas/'.$encuesta->id;
                $file = $request->archivo;

                //Si no existe el directorio, lo creamos
                if (!file_exists($directory)) {
                    //Creamos el directorio
                    Storage::makeDirectory('public/'.$directory);
                }

                //Obtenemos el tipo de archivo que se esta subiendo
                $extension = strtolower($file->getClientOriginalExtension());
                $nombre = 'audio_'.$encuesta->id;

                $audio = $directory.'/'.$nombre.'.'.$extension;

                Storage::disk('public')->put($audio, \File::get($file));

                $existe = Storage::disk('public')->exists($audio);

                if ($existe) {

                    $encuesta->archivo = $audio;
                }
                
            }

            if ($encuesta->save()) {
                return redirect()->route('encuestas.index')->with('success', 'Registro actualizado con exito!');
            }else{
                return $redirect()->route('error', 'Error al actualizar la pregunta.');
            }
        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('encuestas-eliminar')) {

            $encuesta = EncuestaSatisfaccion::findOrFail($id);

            if (count($encuesta->respuesta_encuesta_cliente) > 0) {
                return redirect()->route('encuestas.index')->with('error', 'No se puede eliminar porque tiene respuestas asociadas!');
            }


            if (!empty($encuesta->archivo)) {
                if (Storage::disk('public')->exists($encuesta->archivo)){
                    Storage::disk('public')->delete($encuesta->archivo);
                }
            }

            if ($encuesta->delete()) {
                return redirect()->route('encuestas.index')->with('success','Encuesta eliminada con exíto!');
            }else{
                return redirect()->route('encuestas.index')->with('error', 'error al eliminar la encuesta!');
            }

        }else{
            abort(403);
        }
    }
}
