<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MantenimientoParadaReloj;
use Illuminate\Support\Facades\Auth;

class MantenimientosParadaRelojController extends Controller
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
    public function store(Request $request, $mantenimiento_id)
    {
        if (Auth::user()->can('mantenimientos-paradas-reloj-crear')) {
            $this->validate($request, [
                'fecha_inicio' => 'required|date',
                'hora_inicio' => 'required',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'hora_fin' => 'required',
                'descripcion' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $horaInicio = explode(':', $request->hora_inicio);
            $horaFin = explode(':', $request->hora_fin);

            $paradaReloj = new MantenimientoParadaReloj();
            $paradaReloj->InicioParadaDeReloj = $request->fecha_inicio;
            $paradaReloj->FinParadaDeReloj = $request->fecha_fin;
            $paradaReloj->DescripcionParada = $request->descripcion;
            $paradaReloj->HoraInicio = $horaInicio[0];
            $paradaReloj->MinInicio = $horaInicio[1];
            $paradaReloj->HoraFin = $horaFin[0];
            $paradaReloj->MinFin = $horaFin[1];
            $paradaReloj->InicioParada = $request->hora_inicio;
            $paradaReloj->FinParada = $request->hora_fin;

            if($request->mantenimiento_tipo == 'PREVENTIVO'){
                $paradaReloj->ProgMantId = $mantenimiento_id;
                $link = "preventivos";
            }else{
                $paradaReloj->MantId = $mantenimiento_id;
                $link = "correctivos";
            }

            if ($paradaReloj->save()) {
                return redirect()->route($link.'.show',$mantenimiento_id)->with('success','Parada de reloj agregada correctamente');
            }else{
                return redirect()->route($link.'.show',$mantenimiento_id)->with('error','Error al agregar la parada de reloj');
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
    public function edit($mantenimiento_id, $parada_reloj_id)
    {
        if (!Auth::user()->can('mantenimientos-paradas-reloj-editar')) {
            abort(403);
            return;
        }

        $parada_reloj = MantenimientoParadaReloj::findOrFail($parada_reloj_id);
        return response()->json($parada_reloj);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mantenimiento_id, $parada_reloj_id)
    {
        if (!Auth::user()->can('mantenimientos-paradas-reloj-editar')) {
            abort(403);
            return;
        }

        $paradaReloj = MantenimientoParadaReloj::findOrFail($parada_reloj_id);

        $this->validate($request, [
            'fecha_inicio' => 'required|date',
            'hora_inicio' => 'required',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'hora_fin' => 'required',
            'descripcion' => 'required',
            'mantenimiento_tipo' => 'required'

        ]);

        $horaInicio = explode(':', $request->hora_inicio);
        $horaFin = explode(':', $request->hora_fin);

        $paradaReloj->InicioParadaDeReloj = $request->fecha_inicio;
        $paradaReloj->FinParadaDeReloj = $request->fecha_fin;
        $paradaReloj->DescripcionParada = $request->descripcion;
        $paradaReloj->HoraInicio = $horaInicio[0];
        $paradaReloj->MinInicio = $horaInicio[1];
        $paradaReloj->HoraFin = $horaFin[0];
        $paradaReloj->MinFin = $horaFin[1];
        $paradaReloj->InicioParada = $request->hora_inicio;
        $paradaReloj->FinParada = $request->hora_fin;

        $link = ($request->mantenimiento_tipo == 'PREVENTIVO')? "preventivos" : "correctivos";       


        if ($paradaReloj->update()) {
            return redirect()->route($link.'.show',$mantenimiento_id)->with('success','Parada de reloj editada correctamente');
        }else{
            return redirect()->route($link.'.show',$mantenimiento_id)->with('error','Error al editar la parada de reloj');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($mantenimiento_id, $parada_reloj_id)
    {
        if (Auth::user()->can('mantenimientos-paradas-reloj-eliminar')) {
            $parada_reloj = MantenimientoParadaReloj::findOrFail($parada_reloj_id);
            
            $link= (!empty($parada_reloj->ProgMantId))? "preventivos" : "correctivos";
            
            if ($parada_reloj->delete()) {
                return redirect()->route($link.'.show', $mantenimiento_id)->with('success','Parada de reloj eliminada correctamente');
            }else{
                return redirect()->route($link.'.show', $mantenimiento_id)->with('error','No se pudo eliminar.');
            }
        
        }else{
            abort(403);
        }

    }
}
