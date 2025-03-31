<?php

namespace App\Http\Controllers;

use App\ActivoFijo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InfraestructurasEquipos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class InfraestructurasEquiposController extends Controller
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
    public function store(Request $request, $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-equipos-crear')) {
            return abort(403);
        }

        $this->validate($request, [
            'serial' => 'required|exists:ActivosFijos,Serial',
            'ip_gestion' => 'nullable',
            'usuario' => 'nullable',
            'password' => 'nullable'
        ]);

        $equipo = new InfraestructurasEquipos();
        $equipo->ip_gestion = $request->ip_gestion;
        $equipo->usuario = $request->usuario;
        $equipo->password = $request->password !== null ? Crypt::encrypt($request->password) : null;
        $equipo->inventario_id = ActivoFijo::where('Serial', '=', $request->serial)->pluck('ActivoFijoId')->first();
        $equipo->infraestructura_id = $infraestructura;

        if($equipo->save()){
            return redirect()->route('infraestructuras.show', $infraestructura)->with('success', 'Equipo creado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show', $infraestructura)->with('error', 'Error al crear el equipo.');
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
    public function edit($infraestructura, $equipo_id)
    {
        if (!Auth::user()->can('infraestructura-equipos-editar')) {
            return abort(403);
        }
        $equipo = InfraestructurasEquipos::findOrFail($equipo_id);
        $equipo->activo_fijo->insumo;
        $equipo->password = isset($equipo->password) ? Crypt::decrypt($equipo->password) : null;
        return response()->json($equipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $infraestructura, $equipo_id)
    {
        if (!Auth::user()->can('infraestructura-equipos-editar')) {
            return abort(403);
        }

        $this->validate($request, [
            'ip_gestion' => 'nullable',
            'usuario' => 'nullable',
            'password' => 'nullable'
        ]);

        $equipo = InfraestructurasEquipos::findOrFail($equipo_id);
        $equipo->ip_gestion = $request->ip_gestion;
        $equipo->usuario = $request->usuario;
        $equipo->password = $request->password !== null ? Crypt::encrypt($request->password) : null;

        if($equipo->save()){
            return redirect()->route('infraestructuras.show', $infraestructura)->with('success', 'Equipo actualizado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show', $infraestructura)->with('error', 'Error al actualizar el equipo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($infraestructura, $equipo_id)
    {
        if (!Auth::user()->can('infraestructura-equipos-eliminar')) {
            return abort(403);
        }

        $equipo = InfraestructurasEquipos
        ::findOrFail($equipo_id);

        if ($equipo->delete()) {
            return redirect()->route('infraestructuras.show',$infraestructura)->with('success','Equipo eliminado correctamente.');
        }else{
            return redirect()->route('infraestructuras.show',$infraestructura)->with('error','Error al eliminar el equipo.');
        }
    }

}
