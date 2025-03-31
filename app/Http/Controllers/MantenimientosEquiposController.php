<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoEquipo;

class MantenimientosEquiposController extends Controller
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
     * @param  int  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $mantenimiento)
    {
        if (Auth::user()->can('mantenimientos-equipos-crear')) {
            $this->validate($request, [
                'nombre' => 'required',
                'marca' => 'required',
                'serial' => 'required',
                'cambio' => 'required',
                'mantenimiento_tipo' => 'required'

            ]);

            $equipo = new MantenimientoEquipo;
            $equipo->Equipo = $request->nombre;
            $equipo->MarcaReferencia = $request->marca;
            $equipo->Serial = $request->serial;
            $equipo->RealizoCambio = $request->cambio;
            $equipo->Observaciones = $request->observaciones;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $equipo->ProgMantid = $mantenimiento;
                $link = "preventivos.show";
            }else{
                $equipo->MantId = $mantenimiento;
                $link = "correctivos.show";
            }

            $link = (!empty($request->link)? $request->link : $link);

            if ($equipo->save()) {
                return redirect()->route($link,$mantenimiento)->with('success','Equipos agregado correctamente.');
            }else{
                return redirect()->route($link,$mantenimiento)->with('error','Error al agregar el equipo.');
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
    public function edit($mantenimiento, $equipo_id)
    {
        if (!Auth::user()->can('mantenimientos-equipos-editar')) {
            abort(403);
            return;
        }

        $equipo = MantenimientoEquipo::findOrFail($equipo_id);
        return response()->json($equipo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mantenimiento_id, $equipo_id)
    {        
        if (!Auth::user()->can('mantenimientos-equipos-editar')) {
            abort(403);
            return;
        }

        $this->validate($request, [
            'nombre' => 'required',
            'marca' => 'required',
            'serial' => 'required',
            'cambio' => 'required',
            'mantenimiento_tipo' => 'required'
        ]);

        $equipo = MantenimientoEquipo::findOrFail($equipo_id);
        $equipo->Equipo = $request->nombre;
        $equipo->MarcaReferencia = $request->marca;
        $equipo->Serial = $request->serial;
        $equipo->RealizoCambio = $request->cambio;
        $equipo->Observaciones = $request->observaciones;

        if($request->mantenimiento_tipo == 'PREVENTIVO'){
            $link = "preventivos.show";
        }else{
            $link = "correctivos.show";
        }

        $link = (!empty($request->link)? $request->link : $link);

        if ($equipo->update()) {
            return redirect()->route($link,$mantenimiento_id)->with('success','Equipo actualizado correctamente');
        }else{
            return redirect()->route($link,$mantenimiento_id)->with('error','Error al actualizar el equipo');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($mantenimiento, $equipo_id)
    {
        if (!Auth::user()->can('mantenimientos-equipos-eliminar')) {
            abort(403);
            return;
        }

        $equipo = MantenimientoEquipo::findOrFail($equipo_id);

        if(!empty($equipo->ProgMantid)){
            $link= "preventivos";
        }else{
            $link= "correctivos";
        }
        
        if ($equipo->delete()) {

            return redirect()->route($link.'.show', $mantenimiento)->with('success','Equipo eliminado');
        }else{
            return redirect()->route($link.'.show', $mantenimiento)->with('error','No se pudo eliminar.');
        }

    }
}
