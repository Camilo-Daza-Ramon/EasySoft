<?php

namespace App\Http\Controllers;

use App\DocumentalCarpeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DocumentalCarpetaController extends Controller
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
    public function show(DocumentalCarpeta $documental_carpeta)
    {
        if (!Auth::user()->can('documental-proyectos-ver')) {
            return abort(403);
        }

        $documentales = $documental_carpeta->documental()->orderBy('nombre', 'asc')->get();
        $carpetas = $documental_carpeta->subcarpetas()->get();
        $documental_lista = collect()->merge($carpetas)->merge($documentales);

        $tipos = ['VERSION','MENSUAL','CARPETA'];

        $proyecto = $documental_carpeta->proyecto;
        $ruta = null;
        if ($proyecto !== null) {
            $ruta = $proyecto->NumeroDeProyecto . ' / ' . $documental_carpeta->nombre;
        } else {
            $carpeta = $documental_carpeta;
            while ($carpeta->proyecto === null) {
                $ruta = $carpeta->nombre . ' / ' . $ruta;
                $carpeta = $carpeta->carpeta_padre;
            }
            $ruta = $carpeta->proyecto->NumeroDeProyecto . ' / ' . $carpeta->nombre . ' / ' . $ruta;
        }

        return view('adminlte::proyectos.gestion-documental.carpetas.index',[
            'carpeta' => $documental_carpeta,
            'documental_lista' => $documental_lista,
            'proyecto' => $proyecto,
            'tipos' => $tipos,
            'ruta' => $ruta
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentalCarpeta $documental_carpeta)
    {
        if (Auth::user()->can('documental-proyectos-editar')) {
            return response()->json($documental_carpeta);
        }else{
            abort(403);
        }      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $carpeta_id)
    {
        if (Auth::user()->can('documental-proyectos-editar')) {

            $this->validate(request(),[
                'nombre' => 'required',
            ]);
            $carpeta = DocumentalCarpeta::findOrFail($carpeta_id);
            $carpeta->nombre = $request->nombre;

            if($carpeta->save()){
                return redirect()->back()->with('success', 'Información actualizada correctamente.');
            }else{
                return redirect()->back()->with('error', 'Error al actualizar la información.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($carpeta_id)
    {
        if (Auth::user()->can('documental-proyectos-eliminar')) {
            $documental_carpeta = DocumentalCarpeta::findOrFail($carpeta_id);

            if($documental_carpeta->documental()->count() > 0){
                return redirect()->back()->with('warning', 'No es posible eliminar porque tiene versiones asociadas.');
            }

            if($documental_carpeta->delete()){
                return redirect()->back()->with('success', 'Información eliminada correctamente.');
            }else{
                return redirect()->back()->with('error', 'Error al eliminar la información');
            }

        }else{
            abort(403);
        }
    }
}
