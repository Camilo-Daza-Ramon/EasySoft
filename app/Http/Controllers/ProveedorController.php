<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Departamento;
use App\Proyecto;


class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('proveedores-listar')) {

            $proveedores = Proveedor::paginate(15);

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            $estados = ['ACTIVO','INACTIVO'];
            $tipos_identificacion = ['CC','NIT'];
            $categorias = ['NODO PRIMARIO','NODO SECUNDARIO', 'ZONA WIFI', 'TORRE'];
            $tipos_categorias = ['COMUNIDAD DE CONECTIVIDAD','PUNTO DE ACCESO COMUNITARIO'];


            return view('adminlte::proveedores.index', compact(
                'proveedores',
                'departamentos', 
                'estados', 
                'proyectos', 
                'tipos_identificacion',
                'categorias',
                'tipos_categorias'
            ));


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
        if (Auth::user()->can('proveedores-crear')) {

            $this->validate(request(),[
                'tipo_identificacion' => 'required',
                'identificacion' => 'required|unique:proveedores',
                'nombre' => 'required',
                'tipo' => 'required',
                'direccion' => 'required',
                'municipio' => 'required',
                'correo_electronico' => 'required',
            ]);


            $proveedor = new Proveedor();
            $proveedor->nombre = $request->nombre;
            $proveedor->tipo_identificacion = $request->tipo_identificacion;
            $proveedor->identificacion = $request->identificacion;
            $proveedor->tipo = $request->tipo;
            $proveedor->direccion = $request->direccion;
            $proveedor->municipio_id = $request->municipio;
            $proveedor->estado = 'ACTIVO';
            $proveedor->telefono = $request->telefono;
            $proveedor->celular = $request->celular;
            $proveedor->correo_electronico = $request->correo_electronico;

            if($proveedor->save()){
                return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
            }else{
                return redirect()->route('proveedores.index')->with('error', 'Error al crear el proveedor.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        if (Auth::user()->can('proveedores-ver')) {

            return view('adminlte.proveedores.show', $proveedor);

        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        if (Auth::user()->can('proveedores-editar')) {
            $proveedor->municipio;
            return response()->json(['proveedor' => $proveedor]);

        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        if (Auth::user()->can('proveedores-editar')) {

            $this->validate(request(),[
                'tipo_identificacion' => 'required',
                'identificacion' => 'required',
                'nombre' => 'required',
                'tipo' => 'required',
                'direccion' => 'required',
                'municipio' => 'required',
                'correo_electronico' => 'required'
            ]);

            $proveedor->nombre = $request->nombre;
            $proveedor->tipo_identificacion = $request->tipo_identificacion;
            $proveedor->identificacion = $request->identificacion;
            $proveedor->tipo = $request->tipo;
            $proveedor->direccion = $request->direccion;
            $proveedor->municipio_id = $request->municipio;
            $proveedor->telefono = $request->telefono;
            $proveedor->celular = $request->celular;
            $proveedor->correo_electronico = $request->correo_electronico;

            if($proveedor->save()){
                return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
            }else{
                return redirect()->route('proveedores.index')->with('error', 'Error al crear el proveedor.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
        if (Auth::user()->can('proveedores-eliminar')) {

            if($proveedor->delete()){
                return redirect()->route('proveedores.index')->with('success','Proveedor eliminado correctamente.');
            }else{
                return redirect()->route('proveedores.index')->with('error','Error al eliminar el proveedor.');
            }

        }else{
            abort(403);
        }
    }
}
