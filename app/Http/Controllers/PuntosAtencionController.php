<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\PuntoAtencion;
use App\Proyecto;
use App\PuntoAtencionArea;
use App\User;

class PuntosAtencionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('puntos-atencion-listar')) {

            $puntos_atencion = PuntoAtencion::paginate(15);
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            return view('adminlte::atencion-clientes.puntos-atencion.index', compact('puntos_atencion', 'proyectos'));
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
        if (Auth::user()->can('puntos-atencion-crear')) {
            $this->validate(request(),[
                'nombre' => 'required',            
                'direccion' => 'required',
                'municipio' => 'required',
                'barrio' => 'required',
                'proyecto' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'estado' => 'required']
            );

            $punto_atencion = new PuntoAtencion;
            $punto_atencion->nombre = $request->nombre;
            $punto_atencion->direccion = $request->direccion;
            $punto_atencion->barrio = $request->barrio;
            $punto_atencion->latitud = $request->latitud;
            $punto_atencion->longitud = $request->longitud;
            $punto_atencion->municipio_id = $request->municipio;
            $punto_atencion->proyecto_id = $request->proyecto;
            $punto_atencion->estado = $request->estado;

            if ($punto_atencion->save()) {
                return redirect()->route('puntos-atencion.index')->with('success','Registro agregado correctamente!');
            }else{
                return redirect()->route('puntos-atencion.index')->with('error','Error al agregar registro!');
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
        $punto_atencion = PuntoAtencion::findOrFail($id);
        $areas = PuntoAtencionArea::where('punto_atencion_id', $id)->get();
        $asesores = User::select('users.id','users.name')->whereHas('roles', function($q){
                        $q->where('roles.name', '=', 'asesor-punto-atencion');
                    })
                    ->leftJoin('puntos_atencion_ventanillas', 'users.id', '=', 'puntos_atencion_ventanillas.user_id')
                    ->whereNull('puntos_atencion_ventanillas.user_id')
                    ->where('estado','ACTIVO')
                    ->orderBy('name','ASC')->get();
        return view('adminlte::atencion-clientes.puntos-atencion.show', compact('punto_atencion', 'areas','asesores'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('puntos-atencion-actualizar')) {
            $punto_atencion = PuntoAtencion::findOrFail($id);
            $estados = array('ACTIVO', 'INACTIVO');
            $proyectos = Proyecto::where('status', 'A')->get();

            return view('adminlte::atencion-clientes.puntos-atencion.edit', compact('punto_atencion', 'estados', 'proyectos'));

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
        if (Auth::user()->can('puntos-atencion-actualizar')) {
            $this->validate(request(),[
                'nombre' => 'required',            
                'direccion' => 'required',
                'municipio' => 'required',
                'barrio' => 'required',
                'proyecto' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'estado' => 'required']
            );

            $punto_atencion = PuntoAtencion::find($id);
            $punto_atencion->nombre = $request->nombre;
            $punto_atencion->direccion = $request->direccion;
            $punto_atencion->barrio = $request->barrio;
            $punto_atencion->latitud = $request->latitud;
            $punto_atencion->longitud = $request->longitud;
            $punto_atencion->municipio_id = $request->municipio;
            $punto_atencion->proyecto_id = $request->proyecto;
            $punto_atencion->estado = $request->estado;

            if ($punto_atencion->save()) {
                return redirect()->route('puntos-atencion.edit', $id)->with('success','Registro actualizado correctamente!');
            }else{
                return redirect()->route('puntos-atencion.edit', $id)->with('error','Error al actualizar el registro!');
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
        if (Auth::user()->can('puntos-atencion-eliminar')) {

            $punto_atencion = PuntoAtencion::findOrFail($id);

            if (count($punto_atencion->punto_atencion_area) > 0) {
                return redirect()->route('puntos-atencion.index')->with('error', 'No se puede eliminar porque tiene areas asociadas!');
            }

            if (count($punto_atencion->punto_atencion_cliente) > 0) {
                return redirect()->route('puntos-atencion.index')->with('error', 'No se puede eliminar porque tiene atenciones asociadas!');
            }

            if ($punto_atencion->delete()) {
                return redirect()->route('puntos-atencion.index')->with('success','Encuesta eliminada con exíto!');
            }else{
                return redirect()->route('puntos-atencion.index')->with('error', 'error al eliminar la encuesta!');
            }

        }else{
            abort(403);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cliente($id)
    {
        return view('adminlte::atencion-clientes.puntos-atencion.cliente');
    }

}
