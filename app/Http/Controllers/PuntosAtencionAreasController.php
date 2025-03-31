<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\PuntoAtencionArea;

class PuntosAtencionAreasController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$punto)
    {


        if (Auth::user()->can('puntos-atencion-areas-crear')) {
             $this->validate(request(),[
                'nombre' => 'required']);

             $area = new PuntoAtencionArea;
             $area->nombre = $request->nombre;
             $area->punto_atencion_id = $punto;

             if ($area->save()) {
                return redirect()->route('puntos-atencion.show', $punto)->with('success','Registro agregado correctamente!');
            }else{
                return redirect()->route('puntos-atencion.show', $punto)->with('error','Error al agregar registro!');
            }
        }else{
            abort(403);
        }
        
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
    public function destroy($punto,$id)
    {
        
    }
}
