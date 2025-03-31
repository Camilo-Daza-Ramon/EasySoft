<?php

namespace App\Http\Controllers;

use App\DocumentalVersion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;


class DocumentalVersionController extends Controller
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
        if (Auth::user()->can('documental-versiones-crear')) {
            $this->validate(request(),[
                'titulo' => 'required',
                'version' => 'required',
                'estado' => 'required'
            ]);

            $version =  new DocumentalVersion;
            $version->titulo = $request->titulo;
            $version->version = $request->version;
            $version->documental_proyecto_id = $documental_proyecto;
            $version->estado = $request->estado;

            if(!empty($request->periodo)) {
                $version->documental_mensual_id = $request->periodo;
            }

            if($version->save()){
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
     * @param  \App\DocumentalVersion  $version
     * @return \Illuminate\Http\Response
     */
    public function show($documental_proyecto, DocumentalVersion $versione)
    {
        if (Auth::user()->can('documental-versiones-ver')) {

            return response()->json(['archivos' => $versione->archivos]);

        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentalVersion  $version
     * @return \Illuminate\Http\Response
     */
    public function edit($documental_proyecto, DocumentalVersion $versione)
    {
        if (Auth::user()->can('documental-versiones-editar')) {
            return response()->json(['version' => $versione]);
        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentalVersion  $version
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $documental_proyecto, DocumentalVersion $versione)
    {
        if (Auth::user()->can('documental-versiones-editar')) {

            $this->validate(request(),[
                'titulo' => 'required',
                'version' => 'required',
                'estado' => 'required'
            ]);

            $versione->titulo = $request->titulo;
            $versione->version = $request->version;
            $versione->estado = $request->estado;

            if(!empty($request->periodo)) {
                $versione->documental_mensual_id = $request->periodo;
            }

            if($versione->save()){
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
     * @param  \App\DocumentalVersion  $version
     * @return \Illuminate\Http\Response
     */
    public function destroy($documental_proyecto, DocumentalVersion $versione)
    {
        if (Auth::user()->can('documental-versiones-eliminar')) {

            if($versione->archivos->count() > 0){
                return redirect()->route('documental-proyectos.show', $documental_proyecto)->with('warning', 'No se puede eliminar porque tiene archivos relacionados!');
            }else{

                if($versione->delete()){
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
