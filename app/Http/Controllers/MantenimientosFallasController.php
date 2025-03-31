<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MantenimientoFalla;
use App\TipoFallo;
use Illuminate\Support\Facades\Auth;
use DB;


class MantenimientosFallasController extends Controller
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
        if (Auth::user()->can('mantenimientos-fallos-crear')) {

            $this->validate($request, [
                'fallos.*' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $link = ($request->mantenimiento_tipo == 'PREVENTIVO')? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            $result = DB::transaction(function () use($request, $mantenimiento) {

                foreach ($request->fallos as $key => $value) {
                
                    $tipoFallo = TipoFallo::findOrFail($value)->DescipcionFallo;
        
                    $falla = new MantenimientoFalla();
                    $falla->TipoFallaId = $value;
                    $falla->MantId = $mantenimiento;
                    $falla->Observacion = $tipoFallo;
                    if(!$falla->save()){
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al agregar las Fallas!'];
                    }
                }

                return ['tipo_mensaje' => 'success', 'mensaje' => 'Fallas agregadas correctamente'];

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
    public function destroy($mantenimiento_id, $falla_id)
    {
        if (Auth::user()->can('mantenimientos-fallos-eliminar')) {

            $falla = MantenimientoFalla::findOrFail($falla_id);

            $link= (!empty($falla->mantenimiento_preventivo_id))? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            if ($falla->delete()) {
                return redirect()->route($link, $mantenimiento_id)->with('success','Falla eliminada correctamente');
            }else{
                return redirect()->route($link, $mantenimiento_id)->with('error','No se pudo eliminar.');
            }

        }else{
            abort(403);
        }
    }
}
