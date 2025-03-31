<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MantenimientoMaterial;
use Illuminate\Support\Facades\Auth;

class MantenimientosMaterialesController extends Controller
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
    public function store(Request $request, $mantenimiento)
    {
        if (Auth::user()->can('mantenimientos-materiales-crear')) {

            $this->validate($request, [
                'cantidad' => 'required|integer',
                'unidad' => 'required|string',
                'insumo' => 'required|exists:InsumosBasicos,InsumoId',
                'mantenimiento_tipo' => 'required'
            ]);

            $material = new MantenimientoMaterial();
            $material->Unidad = $request->unidad;
            $material->Cantidad = $request->cantidad;
            $material->Descripcion = $request->descripcion;
            $material->MantId = $mantenimiento;
            $material->InsumoId = $request->insumo;
            
            $link = "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            if ($material->save()) {
                return redirect()->route($link,$mantenimiento)->with('success','Material agregado correctamente');
            }else{
                return redirect()->route($link,$mantenimiento)->with('error','Error al agregar el material');
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
    public function edit($mantenimiento_id, $material_id)
    {
        if (Auth::user()->can('mantenimientos-materiales-editar')) {

            $material = MantenimientoMaterial::findOrFail($material_id);
            return response()->json($material);

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
    public function update(Request $request, $mantenimiento_id, $material_id)
    {
        if (Auth::user()->can('mantenimientos-materiales-editar')) {

            $this->validate($request, [
                'cantidad' => 'required',
                'unidad' => 'required',
                'insumo' => 'required',
                'mantenimiento_tipo' => 'required'
            ]);

            $material = MantenimientoMaterial::findOrFail($material_id);
            $material->Unidad = $request->unidad;
            $material->Cantidad = $request->cantidad;
            $material->Descripcion = $request->descripcion;
            $material->MantId = $mantenimiento_id;
            $material->InsumoId = $request->insumo;

            $link = "correctivos.show";
            $link = (!empty($request->link)? $request->link : $link);

            if ($material->update()) {
                return redirect()->route($link,$mantenimiento_id)->with('success','Material actualizado correctamente');
            }else{
                return redirect()->route($link,$mantenimiento_id)->with('error','Error al actualizar el material');
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
    public function destroy($mantenimiento_id, $material_id)
    {
        if (Auth::user()->can('mantenimientos-materiales-eliminar')) {

            $material = MantenimientoMaterial::findOrFail($material_id);
            $link= "correctivos";
        
            if ($material->delete()) {
                return redirect()->route($link.'.show', $mantenimiento_id)->with('success','Material eliminado');
            }else{
                return redirect()->route($link.'.show', $mantenimiento_id)->with('danger','No se pudo eliminar.');
            }
            
        }else{
            abort(403);
        }
    }
}
