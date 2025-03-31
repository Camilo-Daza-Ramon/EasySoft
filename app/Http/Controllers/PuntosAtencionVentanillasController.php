<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\PuntoAtencionVentanilla;

class PuntosAtencionVentanillasController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $punto)
    {
        if (Auth::user()->can('puntos-atencion-ventanillas-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',            
                'area' => 'required',
                'asesor' => 'required']
            );

            $ventanilla = new PuntoAtencionVentanilla;
            $ventanilla->nombre = $request->nombre;
            $ventanilla->punto_atencion_area_id = $request->area;
            $ventanilla->user_id = $request->asesor;

            if ($ventanilla->save()) {
                return redirect()->route('puntos-atencion.show', $punto)->with('success','Registro agregado correctamente!');
            }else{
                return redirect()->route('puntos-atencion.show', $punto)->with('error','Error al crear la ventanilla!');
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
    public function show($punto,$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($punto,$id)
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
    public function update(Request $request, $punto, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($punto, $id)
    {
        if (Auth::user()->can('puntos-atencion-ventanillas-eliminar')) {

            $ventanilla = PuntoAtencionVentanilla::findOrFail($id);

            if ($ventanilla->delete()) {
                return redirect()->route('puntos-atencion.show', $punto)->with('success', 'ventanilla eliminada');

            }else{
                return redirect()->route('puntos-atencion.show', $punto)->with('error', 'Error al eliminar la ventanilla.');
            }

        }else{
            abort(403);
        }
    }
}
