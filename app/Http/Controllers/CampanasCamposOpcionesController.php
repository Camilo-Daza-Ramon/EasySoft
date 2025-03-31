<?php

namespace App\Http\Controllers;

use App\CampanaCamposOpciones;
use Illuminate\Http\Request;

class CampanasCamposOpcionesController extends Controller
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
    public function store(Request $request)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    public function ajax_opciones( $opcion){

        $opcion = CampanaCamposOpciones::find($opcion);

        if($opcion->estado == 1){
            $opcion->estado = 0;        
        }else{
            $opcion->estado = 1;        
        }

        if($opcion->save()){
            return response()->json('La opcion a sido actualizada.');    
        }else{
            return response()->json('La opcion no se pudo actualizar.');    
        }

    }

    public function nueva_opcion( Request $request){
        $opcion = new CampanaCamposOpciones;
        $opcion->valor = $request->opcion;
        $opcion->estado = 1;
        $opcion->campo_id = $request->campo;

        if($opcion->save()){
            return response()->json($opcion->id);    
        }else{
            return response()->json('La opcion no se pudo actualizar.');    
        }
    }
}
