<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Meta;

class MetasController extends Controller
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
        if (Auth::user()->can('proyectos-metas-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'proyecto_id' => 'required',
                'estado' => 'required',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'total_accesos' => 'required'
            ]);

            $meta = new Meta;
            $meta->nombre = $request->nombre;
            $meta->fecha_inicio = $request->fecha_inicio;
            $meta->fecha_fin = $request->fecha_fin;
            $meta->descripcion = $request->descripcion;
            $meta->total_accesos = $request->total_accesos;
            $meta->fecha_aprobacion_interventoria = $request->fecha_aprobacion_interventoria;
            $meta->fecha_aprobacion_supervision = $request->fecha_aprobacion_supervision;
            $meta->estado = $request->estado;
            $meta->ProyectoID = $request->proyecto_id;

            if ($meta->save()) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Meta creada correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al creada la meta!');
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
        if (Auth::user()->can('proyectos-metas-editar')) {

            $meta = Meta::findOrFail($id);           

            return response()->json([
                'meta' => $meta               
            ]);

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
        if (Auth::user()->can('proyectos-metas-editar')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'estado' => 'required',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'total_accesos' => 'required'
            ]);

            $meta = Meta::find($id);
            $meta->nombre = $request->nombre;
            $meta->fecha_inicio = $request->fecha_inicio;
            $meta->fecha_fin = $request->fecha_fin;
            $meta->descripcion = $request->descripcion;
            $meta->total_accesos = $request->total_accesos;
            $meta->fecha_aprobacion_interventoria = $request->fecha_aprobacion_interventoria;
            $meta->fecha_aprobacion_supervision = $request->fecha_aprobacion_supervision;
            $meta->estado = $request->estado;

            if ($meta->save()) {
                return redirect()->route('proyectos.show', $meta->ProyectoID)->with('success', 'Meta actualizada correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->ProyectoID)->with('error', 'Error al actualizar la meta!');
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
        if (Auth::user()->can('proyectos-metas-eliminar')) {

            $meta = Meta::findOrFail($id);

            $proyecto = $meta->ProyectoID;

            if ($meta->delete()) {
                return redirect()->route('proyectos.show', $proyecto)->with('success','Meta eliminada correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al eliminar la meta!');
            }
        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){
        if ($request->ajax()) {

            $metas = Meta::where('ProyectoID', $request->proyecto_id)->orderBy('nombre', 'ASC')->get();

            return response()->json(['metas' => $metas]);

        }
    }
}
