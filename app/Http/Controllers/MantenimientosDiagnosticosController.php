<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MantenimientoDiagnostico;
use DB;

class MantenimientosDiagnosticosController extends Controller
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
        if (Auth::user()->can('mantenimientos-diagnosticos-crear')) {
            $this->validate($request, [
                'diagnostico.*' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $link = ($request->mantenimiento_tipo == 'PREVENTIVO')? "preventivos.show" : "correctivos.show";

            $link = (!empty($request->link)? $request->link : $link);


            $result = DB::transaction(function () use($request, $mantenimiento) {

                foreach ($request->diagnostico as $key => $value) {
                    $diagnostico = new MantenimientoDiagnostico;
                    $diagnostico->DiagnosticoId = $value;
                    
                    if($request->mantenimiento_tipo == 'PREVENTIVO'){
                        $diagnostico->ProgMantId = $mantenimiento;
                    }else{
                        $diagnostico->MantId = $mantenimiento;
                    }

                    if(!$diagnostico->save()){
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al agregar el diagnostico!'];
                    }
                }

                return ['tipo_mensaje' => 'success', 'mensaje' => 'Diagnosticos agregados correctamente.'];

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
    public function destroy(Request $request, $mantenimiento, $diagnostico_id)
    {
        if (Auth::user()->can('mantenimientos-diagnosticos-eliminar')) {

            $diagnostico = MantenimientoDiagnostico::findOrFail($diagnostico_id);
          
            $link= (!empty($diagnostico->ProgMantId))? "preventivos.show" : "correctivos.show";

            $link = (!empty($request->link)? $request->link : $link);


            if ($diagnostico->delete()) {
                return redirect()->route($link, $mantenimiento)->with('success','Diagnostico eliminado correctamente');
            }else{
                return redirect()->route($link, $mantenimiento)->with('error','No se pudo eliminar.');
            }
            
        }else{
            abort(403);
        }
        
    }
}
