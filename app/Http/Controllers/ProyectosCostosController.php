<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ProyectoCosto;

class ProyectosCostosController extends Controller
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
        if (Auth::user()->can('proyectos-costos-crear')) {
            $this->validate(request(),[
                'proyecto_id' => 'required',
                'concepto' => 'required',
                'iva' => 'required',
                'valor' => 'required'                
            ]);

            $existe = ProyectoCosto::where([['concepto', $request->concepto],['proyecto_id', $request->proyecto_id]])->count();

            if ($existe > 0) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('warning', 'El costo que intenta ingresar ya existe!');
            }else{

                $costo = new ProyectoCosto;
                $costo->concepto = $request->concepto;
                $costo->descripcion = $request->descripcion;
                $costo->iva = $request->iva;
                $costo->valor = $request->valor;
                $costo->proyecto_id = $request->proyecto_id;

                if ($costo->save()) {
                    return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Costo agregado correctamente!');
                }else{
                    return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al crear el costo!');
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
        if (Auth::user()->can('proyectos-costos-editar')) {
            $costo = ProyectoCosto::findOrFail($id);

            return response()->json(['costo' => $costo]);
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
        if (Auth::user()->can('proyectos-costos-editar')) {
            $this->validate(request(),[                
                'iva' => 'required',
                'valor' => 'required'                
            ]);           

            $costo = ProyectoCosto::find($id);
            $costo->descripcion = $request->descripcion;
            $costo->iva = $request->iva;
            $costo->valor = $request->valor;

            if ($costo->save()) {
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('success', 'Costo actualizado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $request->proyecto_id)->with('error', 'Error al actualizar el costo!');
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
        if (Auth::user()->can('proyectos-costos-eliminar')) {

            $costo = ProyectoCosto::findOrFail($id);

            $proyecto_id = $costo->proyecto_id;

            if($costo->delete()){
                return redirect()->route('proyectos.show', $proyecto_id)->with('success','El costo se eliminó correctamente!');
            }else{
                return redirect()->route('proyectos.show', $proyecto_id)->with('error','Error al eliminar el costo!');
            }

        }else{
            abort(403);
        }
    }
}
