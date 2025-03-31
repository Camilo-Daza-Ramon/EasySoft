<?php

namespace App\Http\Controllers;

use App\Departamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Infraestructura;
use App\Insumo;
use App\Proveedor;
use App\Proyecto;
use Illuminate\Support\Facades\Auth;

class InfraestructuraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('infraestructura-listar')) {
            return abort(403);
        }

        $infraestructuras = Infraestructura::paginate(15);

        $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
        //$proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
        $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
        ->where(function ($query) {
            if(Auth::user()->proyectos()->count() > 0){
                $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
            }
        })
        ->get();
        $estados = ['ACTIVO','INACTIVO', 'EN INSTALACION'];

        return view('adminlte::infraestructuras.index', compact(
            'infraestructuras',
            'departamentos', 
            'proyectos',
            'estados'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('infraestructura-crear')) {
            return abort(403);
        }

        $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
        $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
        $categorias = ['NODO PRIMARIO', 'NODO SECUNDARIO', 'ZONA WIFI', 'TORRE', 'ANTENA'];
        $tipos_categoria = ['COMUNIDAD DE CONECTIVIDAD', 'PUNTO DE ACCESO COMUNITARIO'];
        $proveedores = Proveedor::select('id', 'identificacion', 'nombre')->get();
        $estados = ['ACTIVO','INACTIVO', 'EN INSTALACION'];
        $infras = Infraestructura::select('id', 'nombre')->get();
        
        return view('adminlte::infraestructuras.create', compact(
            'departamentos',
            'proyectos',
            'categorias',
            'tipos_categoria',
            'proveedores',
            'estados',
            'infras'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('infraestructura-crear')) {
            return abort(403);
        }

        $this->validate(request(),[
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'municipio' => 'required',
            'categoria' => 'required',
            'tipo_categoria' => 'required',
            'direccion' => 'required',
            'proveedor' => 'nullable|exists:proveedores,id',
            'infraestructura_id' => 'nullable|exists:infraestructuras,id',
            'descripcion' => 'nullable',
            'datos_ubicacion' => 'nullable',
        ]);

        $infra = new Infraestructura();
        $infra->nombre = $request->nombre;
        $infra->latitud = $request->latitud;
        $infra->longitud = $request->longitud;
        $infra->municipio_id = $request->municipio;
        $infra->categoria = $request->categoria;
        $infra->tipo_categoria = $request->tipo_categoria;
        $infra->direccion = $request->direccion;
        $infra->estado = 'EN INSTALACION';
        $infra->proveedor_id = $request->proveedor;
        $infra->infraestructura_id = $request->infraestructura_id;
        $infra->descripcion = $request->descripcion;
        $infra->datos_ubicacion = $request->datos_ubicacion;

        if($infra->save()){
            return redirect()->route('infraestructuras.show', $infra->id)->with('success', 'Infraestructura creada correctamente.');
        }else{
            return redirect()->route('infraestructuras.index')->with('error', 'Error al crear la infraestructura.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Infraestructura $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-ver')) {
            return abort(403);
        }
        
        $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
            ->get()->filter(function ($el) use ($infraestructura) {
                return !in_array($el->ProyectoID, $infraestructura->proyectos->pluck('ProyectoID')->toArray()); 
            });
        
        $insumos = Insumo::select(['InsumoId', 'Codigo'])
            ->where('EsActivo', '=', 'Si')->whereNotNull('Codigo')->get();            

        return view('adminlte::infraestructuras.show', compact(
            'infraestructura',
            'proyectos',
            'insumos'
        )); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Infraestructura $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-editar')) {
            return abort(403);
        }

        $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
        $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
        $categorias = ['NODO PRIMARIO', 'NODO SECUNDARIO', 'ZONA WIFI', 'TORRE', 'ANTENA'];
        $tipos_categoria = ['COMUNIDAD DE CONECTIVIDAD', 'PUNTO DE ACCESO COMUNITARIO'];
        $proveedores = Proveedor::select('id', 'identificacion', 'nombre')->get();
        $estados = ['ACTIVO','INACTIVO', 'EN INSTALACION'];
        $infras = Infraestructura::select('id', 'nombre')->get();
        $infraestructura->municipio;
        return view('adminlte::infraestructuras.edit', compact(
            'infraestructura',
            'departamentos',
            'proyectos',
            'categorias',
            'tipos_categoria',
            'proveedores',
            'estados',
            'infras'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Infraestructura $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-editar')) {
            return abort(403);
        }

        $this->validate(request(),[
            'nombre' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
            'municipio' => 'required',
            'categoria' => 'required',
            'tipo_categoria' => 'required',
            'direccion' => 'required',
            'proveedor' => 'nullable|exists:proveedores,id',
            'infraestructura_id' => 'nullable|exists:infraestructuras,id',
            'descripcion' => 'nullable',
            'datos_ubicacion' => 'nullable',
        ]);


        $infraestructura->nombre = $request->nombre;
        $infraestructura->latitud = $request->latitud;
        $infraestructura->longitud = $request->longitud;
        $infraestructura->municipio_id = $request->municipio;
        $infraestructura->categoria = $request->categoria;
        $infraestructura->tipo_categoria = $request->tipo_categoria;
        $infraestructura->direccion = $request->direccion;
        $infraestructura->estado = $request->estado;
        $infraestructura->proveedor_id = $request->proveedor;
        $infraestructura->infraestructura_id = $request->infraestructura_id;
        $infraestructura->descripcion = $request->descripcion;
        $infraestructura->datos_ubicacion = $request->datos_ubicacion;

        if($infraestructura->save()){
            return redirect()->route('infraestructuras.index')->with('success', 'Infraestructura actualizada correctamente.');
        }else{
            return redirect()->route('infraestructuras.index')->with('error', 'Error al actualizar la infraestructura.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Infraestructura $infraestructura)
    {
        if (!Auth::user()->can('infraestructura-eliminar')) {
            return abort(403);
        }

        if ($infraestructura->hijos->count() > 0) {
            return redirect()->route('infraestructuras.index')->with('warning', 'No puedes eliminar una infraestructura que tiene nodos dependientes.');
        }

        if($infraestructura->delete()){
            return redirect()->route('infraestructuras.index')->with('success','Inftraestructura eliminada correctamente.');
        }else{
            return redirect()->route('infraestructuras.index')->with('error','Error al eliminar la infraestructura.');
        }
    }


    public function desasociar_hijos($infraestructura, $dependiente){
        if (!Auth::user()->can('infraestructura-dependientes-eliminar')) {
            return abort(403);
        }

        $hijo = Infraestructura::findOrFail($dependiente);
        $hijo->infraestructura_id = null;
        if($hijo->save()){
            return redirect()->route('infraestructuras.show', $infraestructura)->with('success','Inftraestructura dependiente desasociada correctamente.');
        }else{
            return redirect()->route('infraestructuras.show', $infraestructura)->with('error','Error al desasociar la infraestructura.');
        }
    }
}
