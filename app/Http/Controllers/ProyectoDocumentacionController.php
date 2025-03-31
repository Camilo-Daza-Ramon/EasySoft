<?php

namespace App\Http\Controllers;

use App\ProyectoDocumentacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ProyectoDocumentacionController extends Controller
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
        if (Auth::user()->can('proyectos-documentacion-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'alias' => 'required',
                'estado' => 'required',
                'tipo' => 'required',
            ]);

            $documentacion = new ProyectoDocumentacion;
            $documentacion->proyecto_id = $proyecto;
            $documentacion->nombre = $request->nombre;
            $documentacion->alias = $request->alias;
            $documentacion->descripcion = $request->descripcion;
            $documentacion->tipo = $request->tipo;
            $documentacion->estado = $request->estado;

            if($request->coordenadas){
                $documentacion->coordenadas = $request->coordenadas;
            }

            if($documentacion->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro agregado correctamente.');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al agregar registro.');
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
    public function edit($proyecto,$id)
    {
        if (Auth::user()->can('proyectos-documentacion-editar')) {

            $documentacion = ProyectoDocumentacion::findOrFail($id);

            return response()->json(['documentacion' => $documentacion]);
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
    public function update(Request $request, $proyecto, $id)
    {
        if (Auth::user()->can('proyectos-documentacion-editar')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'alias' => 'required',
                'estado' => 'required',
                'tipo' => 'required',
            ]);

            $documentacion = ProyectoDocumentacion::find($id);

            $documentacion->nombre = $request->nombre;
            $documentacion->alias = $request->alias;
            $documentacion->descripcion = $request->descripcion;
            $documentacion->tipo = $request->tipo;
            $documentacion->estado = $request->estado;

            if($request->coordenadas){
                $documentacion->coordenadas = $request->coordenadas;
            }

            if($documentacion->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro actualizado correctamente.');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al actualizar registro.');
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
    public function destroy($proyecto, $id)
    {
        if (Auth::user()->can('proyectos-documentacion-eliminar')) {

            $documentacion = ProyectoDocumentacion::findOrFail($id);

            if($documentacion->delete()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro eliminado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al eliminar el registro!');
            }

        }else{
            abort(403);
        }
    }
}
