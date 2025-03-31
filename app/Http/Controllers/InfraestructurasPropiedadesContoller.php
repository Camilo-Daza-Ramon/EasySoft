<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InfraestructurasPropiedades;
use Illuminate\Support\Facades\Auth;

class InfraestructurasPropiedadesContoller extends Controller
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
    public function store(Request $request, $infra)
    {
        if (!Auth::user()->can('infraestructura-propiedades-crear')) {
            return abort(403);
        }

        $this->validate($request, [
            'nombre' => 'required',
            'valor' => 'required',
            'unidad_medida' => 'required'
        ]);

        $propiedad = new InfraestructurasPropiedades();
        $propiedad->nombre = $request->nombre;
        $propiedad->valor = $request->valor;
        $propiedad->unidad_medida = $request->unidad_medida;
        $propiedad->infraestructura_id = $infra;

        if ($propiedad->save()) {
            return redirect()->route('infraestructuras.show',$infra)->with('success','Propiedad agregada correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infra)->with('error','Error al agregar la propiedad.');
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
    public function edit($infraestructura, $propiedad)
    {
        if (!Auth::user()->can('infraestructura-propiedades-editar')) {
            return abort(403);
        }
        return response()->json(InfraestructurasPropiedades::findOrFail($propiedad));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $infraestructura, $propiedad)
    {
        if (!Auth::user()->can('infraestructura-propiedades-editar')) {
            return abort(403);
        }

        $propiedad = InfraestructurasPropiedades::findOrFail($propiedad);

        $this->validate($request, [
            'nombre' => 'required',
            'valor' => 'required',
            'unidad_medida' => 'required'
        ]);

        $propiedad->nombre = $request->nombre;
        $propiedad->valor = $request->valor;
        $propiedad->unidad_medida = $request->unidad_medida;

        if ($propiedad->save()) {
            return redirect()->route('infraestructuras.show',$infraestructura)->with('success','Propiedad actualizada correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infraestructura)->with('error','Error al actualizar la propiedad.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($infraestructura, $propiedad)
    {
        if (!Auth::user()->can('infraestructura-propiedades-eliminar')) {
            return abort(403);
        }

        $propiedad = InfraestructurasPropiedades::findOrFail($propiedad);
        
        if ($propiedad->delete()) {
            return redirect()->route('infraestructuras.show',$infraestructura)->with('success','Propiedad eliminada correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infraestructura)->with('error','Error al eliminar la propiedad.');
        }
    }
}
