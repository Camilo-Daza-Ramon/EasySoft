<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoPrueba;
use App\TipoFallo;
use DB;


class MantenimientosPruebasController extends Controller
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
     * @param int $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $mantenimiento)
    {
        if (Auth::user()->can('mantenimientos-pruebas-crear')) {
            $this->validate($request, [
                'prueba.*' => 'required',
                'mantenimiento_tipo' => 'required'

            ]);

            $link = ($request->mantenimiento_tipo == 'PREVENTIVO')? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            $result = DB::transaction(function () use($request, $mantenimiento) {

                foreach ($request->prueba as $key => $value) {
                    
                    $tipoFallo = TipoFallo::findOrFail($value)->DescipcionFallo;

                    $prueba = new MantenimientoPrueba;
                    $prueba->prueba_id = $value;
                    $prueba->descripcion = $tipoFallo;

                    if($request->mantenimiento_tipo == 'PREVENTIVO'){
                        $prueba->mantenimiento_preventivo_id = $mantenimiento;
                    }else{
                        $prueba->mantenimiento_id = $mantenimiento;
                    }

                    if(!$prueba->save()){
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al agregar las pruebas!'];
                    }
                }

                return ['tipo_mensaje' => 'success', 'mensaje' => 'Pruebas agregadas correctamente'];

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
    public function destroy(Request $request, $mantenimiento_id, $prueba_id)
    {
        if (Auth::user()->can('mantenimientos-pruebas-eliminar')) {
            
            $prueba = MantenimientoPrueba::findOrFail($prueba_id);

            $link= (!empty($prueba->mantenimiento_preventivo_id))? "preventivos.show" : "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);


            if ($prueba->delete()) {
                return redirect()->route($link, $mantenimiento_id)->with('success','Prueba eliminada correctamente');
            }else{
                return redirect()->route($link, $mantenimiento_id)->with('error','No se pudo eliminar.');
            }

        }else{
            abort(403);
        }

    }
}
