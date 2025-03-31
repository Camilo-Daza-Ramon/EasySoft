<?php

namespace App\Http\Controllers;

use App\DocumentalMensual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class DocumentalMensualController extends Controller
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
    public function store(Request $request, $documental_proyecto)
    {
        if (Auth::user()->can('documental-mensuales-crear')) {
            $this->validate(request(),[
                'periodo' => 'required',
            ]);

            $mensual =  new DocumentalMensual;
            $mensual->periodo = $request->periodo . '-01';
            $mensual->documental_proyecto_id = $documental_proyecto;

            if($mensual->save()){
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('success', 'Información registrada correctamente.');
            }else{
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('error', 'Error al guardar la información.');
            }
        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DocumentalMensual  $documentalMensual
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentalMensual $mensuale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentalMensual  $mensuale
     * @return \Illuminate\Http\Response
     */
    public function edit($documental_proyecto, DocumentalMensual $mensuale)
    {
        if (Auth::user()->can('documental-mensuales-editar')) {
            return response()->json(['mensual' => $mensuale]);
        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentalMensual  $mensuale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $documental_proyecto, DocumentalMensual $mensuale)
    {
        if (Auth::user()->can('documental-mensuales-editar')) {

            $this->validate(request(),[
                'periodo' => 'required',
            ]);

            $mensuale->periodo = $request->periodo . '-01';

            if($mensuale->save()){
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('success', 'Información actualizada correctamente.');
            }else{
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('error', 'Error al actualizar la información.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentalMensual  $mensuale
     * @return \Illuminate\Http\Response
     */
    public function destroy($documental_proyecto, DocumentalMensual $mensuale)
    {
        if (Auth::user()->can('documental-mensuales-eliminar')) {

            if($mensuale->versiones->count() > 0){
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('warning', 'No se puede eliminar porque tiene versiones relacionadas!');
            }else{

                if($mensuale->delete()){
                    return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('success', 'Información eliminada correctamente.');
                }else{
                    return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('error', 'Error al eliminar la información.');
                }
            }

        }else{
            abort(403);
        }
    }
}
