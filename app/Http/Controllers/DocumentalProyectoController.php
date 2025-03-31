<?php

namespace App\Http\Controllers;

use App\DocumentalCarpeta;
use App\DocumentalProyecto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocumentalMensual;


use Auth;


class DocumentalProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('documental-proyectos-listar')) {

            $documental_lista = DocumentalProyecto::OrderBy('nombre')->paginate(15);

            return view('adminilte::documental-proyectos');

        }else{
            abort(403);
        }
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
        if (Auth::user()->can('documental-proyectos-crear')) {

            $this->validate(request(),[
                'nombre' => 'required',
                'tipo' => 'required',
            ]);

            $documental = null;
            if ($request->tipo === 'CARPETA') {
                $documental = new DocumentalCarpeta();
                $documental->nombre = $request->nombre;
                if ($request->carpeta_id !== null) {
                    $documental->documental_carpeta_id = $request->carpeta_id;
                } else {
                    $documental->proyecto_id = $request->proyecto_id;
                }

            } else {
                $documental =  new DocumentalProyecto;
                $documental->nombre = $request->nombre;
                $documental->tipo = $request->tipo;
                $documental->proyecto_id = $request->proyecto_id;
                if ($request->carpeta_id !== null) {
                    $documental->documental_carpeta_id = $request->carpeta_id;
                } else {
                    $documental->proyecto_id = $request->proyecto_id;
                }
            }


            if($documental->save()){
                return redirect()->back()->with('success', 'Información registrada correctamente.');
                //return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Información registrada correctamente.');
            }else{
                return redirect()->back()->with('error', 'Error al guardar la información.');
                //return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al guardar la información.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DocumentalProyecto  $documentalProyecto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, DocumentalProyecto $documental_proyecto)
    {
        if (Auth::user()->can('documental-proyectos-ver')) {
            $versiones = null;
            $informes_mensuales = $documental_proyecto->mensuales()->orderBy('periodo', 'ASC')->get();

            $periodo = null;

            if(!empty($request->get('periodo'))){
                $periodo =  DocumentalMensual::findOrFail($request->get('periodo'));
                $versiones = $periodo->versiones()->orderBy('version', 'ASC')->get();

            }else{
                $versiones = $documental_proyecto->versiones()->orderBy('version', 'ASC')->get();
            }

            $estados = ['PENDIENTE', 'APROBADO','RECHAZADO'];

            $proyecto = null;
            if ($documental_proyecto->proyecto === null) {
                $carpeta = $documental_proyecto->carpeta;
                while ($carpeta->proyecto === null) {
                    $carpeta = $carpeta->carpeta_padre;
                }
                $proyecto = $carpeta->proyecto->NumeroDeProyecto;
            }

            return view('adminlte::proyectos.gestion-documental.show', 
            compact(
                'documental_proyecto',
                'versiones','estados', 
                'informes_mensuales', 
                'periodo',
                'proyecto'
            ));
        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DocumentalProyecto  $documentalProyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentalProyecto $documental_proyecto)
    {
        if (Auth::user()->can('documental-proyectos-editar')) {
            return response()->json($documental_proyecto);
        }else{
            abort(403);
        }        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DocumentalProyecto  $documental_proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentalProyecto $documental_proyecto)
    {
        if (Auth::user()->can('documental-proyectos-editar')) {

            $this->validate(request(),[
                'nombre' => 'required',
                'tipo' => 'required',
            ]);
            
            $documental_proyecto->nombre = $request->nombre;
            $documental_proyecto->tipo = $request->tipo;

            if($documental_proyecto->save()){
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Información actualizada correctamente.');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al actualizar la información.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DocumentalProyecto  $documental_proyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentalProyecto $documental_proyecto)
    {
        if (Auth::user()->can('documental-proyectos-eliminar')) {

            if($documental_proyecto->versiones()->count() > 0){
                return redirect()->route('proyectos.show', $documental_proyecto->proyecto_id)->with('warning', 'No es posible eliminar porque tiene versiones asociadas.');
            }

            if($documental_proyecto->delete()){
                return redirect()->route('proyectos.show', $documental_proyecto->proyecto_id)->with('success', 'Información eliminada correctamente.');
            }else{
                return redirect()->route('proyectos.show', $documental_proyecto->proyecto_id)->with('error', 'Error al eliminar la información');
            }

        }else{
            abort(403);
        }
    }
}
