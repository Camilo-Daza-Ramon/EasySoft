<?php

namespace App\Http\Controllers;

use App\ProyectoTipoBeneficiario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProyectoTipoBeneficiarioController extends Controller
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
    public function store(Request $request, $proyecto)
    {
        if (Auth::user()->can('proyectos-tipos-beneficiarios-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'estado' => 'required',
            ]);

            $tipo_beneficiario = new ProyectoTipoBeneficiario;
            $tipo_beneficiario->proyecto_id = $proyecto;
            $tipo_beneficiario->nombre = $request->nombre;
            $tipo_beneficiario->descripcion = $request->descripcion;
            $tipo_beneficiario->estado = $request->estado;

            if($tipo_beneficiario->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro agregado correctamente.');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al agregar registro.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProyectoTipoBeneficiario  $proyectoTipoBeneficiario
     * @return \Illuminate\Http\Response
     */
    public function show(ProyectoTipoBeneficiario $proyectoTipoBeneficiario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProyectoTipoBeneficiario  $proyectoTipoBeneficiario
     * @return \Illuminate\Http\Response
     */
    public function edit($proyecto, ProyectoTipoBeneficiario $tipos_beneficiario)
    {
        if (Auth::user()->can('proyectos-tipos-beneficiarios-editar')) {

            return response()->json(['tipo_beneficiario' => $tipos_beneficiario]);
        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProyectoTipoBeneficiario  $proyectoTipoBeneficiario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $proyecto, ProyectoTipoBeneficiario $tipos_beneficiario)
    {
        if (Auth::user()->can('proyectos-tipos-beneficiarios-editar')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'estado' => 'required',
            ]);

            $tipos_beneficiario->nombre = $request->nombre;
            $tipos_beneficiario->descripcion = $request->descripcion;
            $tipos_beneficiario->estado = $request->estado;

            if($tipos_beneficiario->save()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro actualizado correctamente.');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al actualizar registro.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProyectoTipoBeneficiario  $proyectoTipoBeneficiario
     * @return \Illuminate\Http\Response
     */
    public function destroy($proyecto, ProyectoTipoBeneficiario $tipos_beneficiario)
    {
        if (Auth::user()->can('proyectos-tipos-beneficiarios-eliminar')) {

            if($tipos_beneficiario->delete()){
                return redirect()->route('proyectos.show', $proyecto)->with('success','Registro eliminado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto)->with('error','Error al eliminar el registro!');
            }

        }else{
            abort(403);
        }
    }
}
