<?php

namespace App\Http\Controllers;

use App\ProyectoPregunta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Proyecto;


class ProyectoPreguntaController extends Controller
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
    public function store(Request $request, $proyecto)
    {
        if (Auth::user()->can('proyectos-preguntas-crear')) {

            $this->validate(request(),[
                'pregunta' => 'required',
                'tipo' => 'required',
                'estado' => 'required'
            ]);

            $pregunta = new ProyectoPregunta;
            $pregunta->pregunta = $request->pregunta;
            $pregunta->tipo = $request->tipo;
            $pregunta->opciones_respuesta = (!empty($request->respuestas))? json_encode($request->respuestas, JSON_UNESCAPED_UNICODE) : null;
            $pregunta->obligatoriedad = ($request->obligatorio == "1")? true : false;
            $pregunta->proyecto_id = $proyecto;
            $pregunta->estado = $request->estado;

            if($pregunta->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Pregunta agregada correctamente!');

            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al guardar la pregunta.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProyectoPregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto, ProyectoPregunta $pregunta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProyectoPregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function edit($proyecto, ProyectoPregunta $pregunta)
    {
        if (Auth::user()->can('proyectos-preguntas-editar')) {

            return response()->json(['pregunta' => $pregunta]);

        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProyectoPregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $proyecto, ProyectoPregunta $pregunta)
    {
        if (Auth::user()->can('proyectos-preguntas-editar')) {

            $this->validate(request(),[
                'pregunta' => 'required',
                'tipo' => 'required',
                'estado' => 'required'
            ]);

            $pregunta->pregunta = $request->pregunta;
            $pregunta->tipo = $request->tipo;
            $pregunta->opciones_respuesta = (!empty($request->respuestas))? json_encode($request->respuestas, JSON_UNESCAPED_UNICODE) : null;
            $pregunta->obligatoriedad = ($request->obligatorio == "1")? true : false;
            $pregunta->estado = $request->estado;

            if($pregunta->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Pregunta actualizada correctamente!');

            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al actualizar la pregunta.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProyectoPregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyecto $proyecto, ProyectoPregunta $pregunta)
    {
        if (Auth::user()->can('proyectos-preguntas-eliminar')) {

            if($pregunta->respuestas()->count() > 0){
                return redirect()->route('proyectos.show', $proyecto)->with('warning','No se puede eliminar porque tiene respuestas relacionadas.');

            }

            if($pregunta->delete()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Pregunta eliminada correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al eliminar la pregunta.');
            }

        }else{
            abort(403);
        }
    }
}
