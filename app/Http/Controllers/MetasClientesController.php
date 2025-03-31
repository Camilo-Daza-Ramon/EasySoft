<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\MetaCliente;
use App\Cliente;
use App\Proyecto;
use DB;


class MetasClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('metas-clientes-listar')) {

            $metas_clientes = MetaCliente::Buscar($request->get('palabra'))->Proyecto($request->get('proyecto'))->orderBy('id', 'DESC')
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            return view('adminlte::clientes.metas.index', compact('metas_clientes', 'proyectos'));

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
        if (Auth::user()->can('metas-clientes-crear')) {
            $this->validate(request(),[
                'proyecto' => 'required',
                'meta' => 'required',
                'cedulas' => 'required',
            ]);

            $clientes = Cliente::select('ClienteId')
            ->where([
                ['ProyectoId', $request->proyecto],
                ['Status', 'ACTIVO']
            ])
            ->whereIn('Identificacion', explode(",", $request->cedulas))
            ->get();

            if($clientes->count() > 0){

                $result = DB::transaction(function () use($request, $clientes) {

                    foreach ($clientes as $cliente) {
                        $validad = MetaCliente::where([
                            ['ClienteId',$cliente->ClienteId],
                            ['meta_id', $request->meta]
                        ])->count();

                        if($validad == 0){

                            $meta_cliente = new MetaCliente;
                            $meta_cliente->ClienteId = $cliente->ClienteId;
                            $meta_cliente->meta_id = $request->meta;
                            $meta_cliente->idpunto = $cliente->ClienteId;

                            if($meta_cliente->save()){

                                $cliente->reporte = 'GENERADO';
                                if(!$cliente->save()){
                                    DB::rollBack();
                                    return ['error', 'Error al actualizar el cliente.'];
                                }
                                
                            }else{
                                DB::rollBack();
                                return ['error', 'Error al asignar el cliente a la meta.'];
                            }
                        }                        
                    }

                    return ['success', 'Clientes asignados correctamente'];

                });

                return redirect()->route('metas-clientes.index')->with($result[0], $result[1]);

            }else{
                return redirect()->route('metas-clientes.index')->with('warning', 'No hay clientes registrados con esas cedulas');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('metas-clientes-eliminar')) {

            $meta_cliente = MetaCliente::findOrFail($id);

            $result = DB::transaction(function () use($meta_cliente) {            

                if($meta_cliente->delete()){

                    $cliente = $meta_cliente->cliente;
                    $cliente->reporte = null;

                    if(!$cliente->save()){
                        DB::rollBack();
                        return ['error', 'Error al actualizar el cliente.'];
                    }

                    return ['success', 'Asignación eliminada correctamente.'];

                }else{
                    DB::rollBack();
                    return ['error', 'Error al elimnar la asignación!'];                    
                }
            });

            return redirect()->route('metas-clientes.index')->with($result[0], $result[1]);
        }
    }
}
