<?php

namespace App\Http\Controllers;

use App\ClienteRestriccion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Cliente;

use Excel;



class ClienteRestriccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('clientes-restricciones-listar')) {

            $clientes = ClienteRestriccion::with('cliente')
            ->Cedula($request->get('cedula'))->paginate(10);

            return view('adminlte::clientes.restricciones.index', compact('clientes'))
            ->with('i', ($request->input('page', 1) - 1) * 10);;

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
        if (Auth::user()->can('clientes-restricciones-crear')) {

            $this->validate(request(),[
                'cedulas' => 'required'                
            ]);

            $cedulas_no_llamar = explode(',', $request->cedulas);

            $clientes_ids = Cliente::select('ClienteId as cliente_id')
                            ->leftJoin('clientes_restricciones as cr', 'clientes.ClienteId', '=', 'cr.cliente_id')
                            ->whereNull('cr.cliente_id')
                            ->whereIn('Identificacion', $cedulas_no_llamar)
                            ->get();
            if($clientes_ids->count() > 0){

                foreach($clientes_ids as $data){
                    $clientes_restricciones = new ClienteRestriccion;
                    $clientes_restricciones->cliente_id = $data->cliente_id;
                    $clientes_restricciones->observaciones = $request->observaciones;
                    $clientes_restricciones->save();
                }
                
                return redirect()->route('restricciones.index')->with('success', 'Cedulas agregadas correctamente');

            }else{
                return redirect()->route('restricciones.index')->with('error', 'Cedulas ya existen.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClienteRestriccion  $clienteRestriccion
     * @return \Illuminate\Http\Response
     */
    public function show(ClienteRestriccion $restriccione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClienteRestriccion  $clienteRestriccion
     * @return \Illuminate\Http\Response
     */
    public function edit(ClienteRestriccion $restriccione)
    {
        if (Auth::user()->can('clientes-restricciones-editar')) {
          
            return response()->json(array('restriccion' => $restriccione));
            
        }else{
            abort(403);
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClienteRestriccion  $clienteRestriccion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClienteRestriccion $restriccione)
    {
        if (Auth::user()->can('clientes-restricciones-editar')) {
            $restriccione->observaciones = $request->observaciones;
            if($restriccione->save()){
                return redirect()->route('restricciones.index')->with('success', 'Cedula actualizada correctamente');
            }
        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClienteRestriccion  $clienteRestriccion
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClienteRestriccion $restriccione)
    {
        if (Auth::user()->can('clientes-restricciones-eliminar')) {
            if($restriccione->delete()){
                return redirect()->route('restricciones.index')->with('success', 'Cedula eliminada de la restriccion.');
            }else{
                return redirect()->route('restricciones.index')->with('error', 'Error al eliminar la cedula.');
            }
        }else{
            abort(403);
        }
    }

    public function exportar(Request $request){

        if (Auth::user()->can('clientes-restricciones-exportar')) {

            Excel::create('Clientes restricciones', function($excel) use($request) {
    
                $excel->sheet('Clientes restricciones', function($sheet) use($request) {

                
                    $datos = array();

                    $clientes = ClienteRestriccion::get();                

                    foreach ($clientes as $dato) {
                        $datos[] = array(
                            "CEDULA" => $dato->cliente->Identificacion,
                            "NOMBRE" => $dato->cliente->NombreBeneficiario. ' ' .$dato->cliente->Apellidos ,                        
                            "MUNICIPIO" => $dato->cliente->municipio->NombreMunicipio,
                            "DEPARTAMENTO" => $dato->cliente->municipio->departamento->NombreDelDepartamento,
                            "PROYECTO" => $dato->cliente->proyecto->NumeroDeProyecto,
                            "OBSERVACION" => $dato->observaciones                                          
                        );
                    }                

                    if (count($datos) == 0) {
                        return response()->json('error', 'No hay datos para el filtro enviado.');
                    }

                    $sheet->fromArray($datos, true, 'A1', true);
    
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }


}
