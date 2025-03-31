<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProyectoClausula;



class ProyectosClausulasController extends Controller
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
        if (Auth::user()->can('proyectos-clausulas-crear')) {
            $this->validate(request(),[
                'proyecto_id' => 'required',
                'numero_mes' => 'required',
                'valor' => 'required'                
            ]);

            $validar = ProyectoClausula::where([['proyecto_id', $request->proyecto_id], ['numero_mes', $request->numero_mes]])->count();

            if ($validar > 0) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('warning', 'La clausula del MES '.$request->numero_mes .' Ya existe!');
            }else{

                $clausula = new ProyectoClausula;
                $clausula->numero_mes = $request->numero_mes;
                $clausula->valor = $request->valor;
                $clausula->proyecto_id = $request->proyecto_id;

                if ($clausula->save()) {
                    return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Clausula agregada correctamente!');
                }else{
                    return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al crear la clausula!');
                }
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
        if (Auth::user()->can('proyectos-clausulas-editar')) {
            $clausula = ProyectoClausula::findOrFail($id);

            return response()->json(['clausula' => $clausula]);
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
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('proyectos-clausulas-editar')) {
            $this->validate(request(),[                
                'valor' => 'required'
            ]);
            
            $clausula = ProyectoClausula::find($id);

            if ($clausula->numero_mes != $request->numero_mes && !empty($request->numero_mes)) {
                $validar = ProyectoClausula::where([['proyecto_id', $clausula->proyecto_id], ['numero_mes', $request->numero_mes]])->count();

                if ($validar > 0) {
                    return redirect()->route('proyectos.show', $request->proyecto_id)->with('warning', 'La clausula del MES '.$request->numero_mes .' Ya existe!');
                }

                $clausula->numero_mes = $request->numero_mes;
            }


            
            $clausula->valor = $request->valor;

            if ($clausula->save()) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Clausula actualizada correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al actualizar la clausula!');
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
    public function destroy($id)
    {
        if (Auth::user()->can('proyectos-clausulas-eliminar')) {

            $clausula = ProyectoClausula::findOrFail($id);

            $proyecto_id = $clausula->proyecto_id;

            if($clausula->delete()){
                return redirect()->route('proyectos.show', $proyecto_id)->with('success','La clausula se eliminó correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto_id)->with('error','Error al eliminar la clausula!');
            }

        }else{
            abort(403);
        }
    }
}
