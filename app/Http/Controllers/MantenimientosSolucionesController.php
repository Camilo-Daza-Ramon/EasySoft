<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MantenimientoSolucion;
use App\TipoFallo;
use Illuminate\Support\Facades\Auth;
use DB;

class MantenimientosSolucionesController extends Controller
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
    public function store(Request $request, $mantenimiento)
    {
        if (Auth::user()->can('mantenimientos-soluciones-crear')) {
            $this->validate($request, [
                'soluciones.*' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $link = ($request->mantenimiento_tipo == 'PREVENTIVO')? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            $result = DB::transaction(function () use($request, $mantenimiento) {

                foreach ($request->soluciones as $key => $value) {
                
                    $tipoFallo = TipoFallo::findOrFail($value)->DescipcionFallo;
        
                    $solucion = new MantenimientoSolucion();
                    $solucion->solucion_id = $value;
                    $solucion->mantenimiento_id = $mantenimiento;
                    $solucion->descripcion = $tipoFallo;

                    if(!$solucion->save()){
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al agregar las soluciones!'];
                    }
                }

                return ['tipo_mensaje' => 'success', 'mensaje' => 'Soluciones agregadas correctamente'];

            });

            return redirect()->route($link,$mantenimiento)->with($result['tipo_mensaje'],$result['mensaje']);

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
    public function destroy($mantenimiento_id, $solucion_id)
    {
        if (Auth::user()->can('mantenimientos-soluciones-eliminar')) {
            
            $solucion = MantenimientoSolucion::findOrFail($solucion_id);


            $link= (!empty($solucion->mantenimiento_preventivo_id))? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            if ($solucion->delete()) {
                return redirect()->route($link, $mantenimiento_id)->with('success','Prueba eliminada correctamente');
            }else{
                return redirect()->route($link, $mantenimiento_id)->with('error','No se pudo eliminar.');
            }

        }else{
            abort(403);
        }
    }
}
