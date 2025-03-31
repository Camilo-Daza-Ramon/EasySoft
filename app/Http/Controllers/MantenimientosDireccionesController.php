<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoDireccion;

class MantenimientosDireccionesController extends Controller
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
        if (Auth::user()->can('mantenimientos-direcciones-crear')) {
            $this->validate($request, [
                'direccion' => 'required',
                'barrio' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $direccion = new MantenimientoDireccion;
            $direccion->Direccion = $request->direccion;
            $direccion->Barrio = $request->barrio;
            $direccion->Latitud = $request->latitud;
            $direccion->Longitud = $request->longitud;

            $link = null;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $direccion->ProgMantId = $mantenimiento;
                $link = "preventivos.show";
            }else{
                $direccion->MantId = $mantenimiento;
                $link = "correctivos.show";
            }

            $link = (!empty($request->link)? $request->link : $link);

            if ($direccion->save()) {
                return redirect()->route($link,$mantenimiento)->with('success','direccion agregada correctamente');
            }else{
                return redirect()->route($link,$mantenimiento)->with('error','Error al agregar dirección!');
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
    public function edit($mantenimiento, $direccion_id)
    {
        if (!Auth::user()->can('mantenimientos-direcciones-editar')) {
            abort(403);
            return;
        }

        $direccion = MantenimientoDireccion::findOrFail($direccion_id);
        return response()->json($direccion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mantenimiento_id, $direccion_id)
    {
        if (!Auth::user()->can('mantenimientos-direcciones-editar')) {
            abort(403);
            return;
        }

        $this->validate($request, [
            'direccion' => 'required',
            'barrio' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'mantenimiento_tipo' => 'required'

        ]);

        $direccion = MantenimientoDireccion::findOrFail($direccion_id);
        $direccion->Direccion = $request->direccion;
        $direccion->Barrio = $request->barrio;
        $direccion->Latitud = $request->latitud;
        $direccion->Longitud = $request->longitud;

        $link = null;

        if($request->mantenimiento_tipo == 'PREVENTIVO'){
            $link = "preventivos.show";
        }else{
            $link = "correctivos.show";
        }

        $link = (!empty($request->link)? $request->link : $link);

        if ($direccion->update()) {
            return redirect()->route($link,$mantenimiento_id)->with('success','Direccion actualizada correctamente');
        }else{
            return redirect()->route($link,$mantenimiento_id)->with('error','Error al actualizar.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($mantenimiento_id, $direccion_id)
    {
        if (Auth::user()->can('mantenimientos-direcciones-eliminar')) {
            $diagnostico = MantenimientoDireccion::findOrFail($direccion_id);

            $link= (!empty($diagnostico->ProgMantId))? "preventivos.show" : "correctivos.show";

            $link = (!empty($request->link)? $request->link : $link);
            
            if ($diagnostico->delete()) {
                return redirect()->route($link, $mantenimiento_id)->with('success','Dirección eliminada correctamente');
            }else{
                return redirect()->route($link, $mantenimiento_id)->with('error','No se pudo eliminar.');
            }
            
        }else{
            abort(403);
        }
    }
}
