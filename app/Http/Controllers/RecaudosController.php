<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Recaudo;
use App\Proyecto;
use App\Cliente;
use App\Departamento;

use DB;

use Excel;

class RecaudosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('recaudos-ver')) {

            $clientes_inconsistencias = Recaudo::Inconsistencia(true)->count();

            $recaudos = Recaudo::Cedula($request->get('documento'))
                                ->Inconsistencia($request->get('inconsistencia'))
                                ->Fechas($request->get('fecha_desde'), $request->get('fecha_hasta'))
                                ->Proyecto($request->get('proyecto'))
                                ->Departamento($request->get('departamento'))
                                ->Municipio($request->get('municipio'))
                                ->Entidad($request->get('medio_pago'))
                                ->orderBy('Fecha', 'DESC')                                
                                ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            $medios_pagos = ['EFECTY', 'NEQUI'];

            return view('adminlte::facturacion.recaudos.index',compact('recaudos', 'proyectos', 'clientes_inconsistencias', 'departamentos', 'medios_pagos'));
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
        if (Auth::user()->can('recaudos-editar')) {

            $this->validate(request(),[
                'cedula' => 'required',
                'valor' => 'required',
                'referencia' => 'required',
                'medio_pago' => 'required',
                'fecha_hora_pago' => 'required',
            ]);

            

            $result = DB::transaction(function () use($request) {

                $validar = Recaudo::where([
                    ['Referencia',$request->referencia]
                ])->count();
    
                if($validar > 0){
                    return ['tipo_mensaje' => 'error', 'mensaje' => 'El pago ya existe.'];

                }else{

                    $cliente = Cliente::where('Identificacion', $request->cedula)->first();

                    if(!empty($cliente->ClienteId)){

                        $recaudo = new Recaudo;
                        $recaudo->valor = $request->valor;
                        $recaudo->Fecha = date('Y-m-d H:i:s', strtotime($request->fecha_hora_pago));
                        $recaudo->cedula = $cliente->Identificacion;
                        $recaudo->nombres = $cliente->NombreBeneficiario;
                        $recaudo->apellido1 = $cliente->Apellidos;
                        $recaudo->apellido2 = null;
                        $recaudo->campo4 = $cliente->proyecto->NumeroDeProyecto;
                        $recaudo->campo5 = null;
                        $recaudo->ClienteId = $cliente->ClienteId;
                        //$recaudo->Periodo = date('Ym');
                        $recaudo->Referencia = $request->referencia;
                        $recaudo->FechaOriginal = date('Y-m-d H:i:s');
                        $recaudo->RecaudadoPor = $request->medio_pago;
                        $recaudo->user_id = Auth::user()->id;
                        if($recaudo->save()){
                            return ['tipo_mensaje' => 'success', 'mensaje' => 'Recaudo creado correctamente!'];

                        }else{
                            DB::rollBack();
                            return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al crear el recaudo.'];
                        }

                    }else{
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'El cliente no existe.'];
                    }   
                }             
            });

            return redirect()->route('recaudos.index')->with($result['tipo_mensaje'], $result['mensaje']);
            
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

        if (Auth::user()->can('recaudos-editar')) {
            $recaudo = Recaudo::findOrFail($id);

            if (!empty($recaudo->ClienteId)) {
               return redirect()->route('recaudos.index')->with('error', 'No hay inconsistencias para este recaudo.');
           }

            $nombre = explode(' ',$recaudo->nombres);
            $nombre_nuevo = array();

            foreach ($nombre as $key) {
                if (strlen($key) > 0) {
                    $nombre_nuevo[] = $key;
                } 
            }



            $nombre = $nombre_nuevo[0].' '.$nombre_nuevo[1];

            $clientes = Cliente::select('ClienteId','Identificacion', 'NombreBeneficiario', 'Apellidos')
            ->where('NombreBeneficiario', 'like', '%'.$nombre.'%')
            ->orWhere('Apellidos', 'like', '%'.$recaudo->apellido1.'%')
            ->limit(10)
            ->get();


            

            return view('adminlte::facturacion.recaudos.edit',compact('recaudo', 'clientes'));
            

            
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
        if (Auth::user()->can('recaudos-editar')) {

            $this->validate(request(),['cedula' => 'required']);

            $cliente = Cliente::select('ClienteId', 'Identificacion')->where('Identificacion', $request->cedula)->first();

            if (count($cliente) > 0) {
                $recaudo = Recaudo::find($id);
                $recaudo->cedula = $cliente->Identificacion;
                $recaudo->user_id = Auth::user()->id;

                if ($recaudo->save()) {
                    return redirect()->route('recaudos.index')->with('success', 'Cliente asignado correctamente.');
                }else{
                    return redirect()->route('recaudos.index')->with('error', 'Error al guardar.');
                }
            }else{
                return redirect()->route('recaudos.edit', $id)->with('warning', 'no existe cliente con esa cedula.');
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
        if (Auth::user()->can('recaudos-eliminar')) {

            $recaudo = Recaudo::findOrFail($id);

            if($recaudo->delete()){
                return redirect()->route('recaudos.index')->with('success', 'Recaudo eliminado correctamente.');
            }else{
                return redirect()->route('recaudos.index')->with('error', 'Error al eliminar el recaudo.');
            }

        }else{
            abort(403);
        }
    }

    public function exportar(Request $request){

        if (Auth::user()->can('recaudos-exportar')) {
            Excel::create('recaudos', function($excel) use($request) {
     
                $excel->sheet('Recaudos', function($sheet) use($request) {

                    $datos = array();

                    $recaudos = Recaudo::selectRaw('ClientesRecaudos.*,historial_factura_pagoV.total_deuda')
                    ->leftJoin('historial_factura_pagoV','ClientesRecaudos.ClienteId','=','historial_factura_pagoV.ClienteId')
                    ->Fechas($request->fecha_desde, $request->fecha_hasta)
                    ->Cedula($request->documento)
                    ->Proyecto($request->proyecto)
                    ->Departamento($request->departamento)
                    ->Municipio($request->municipio)
                    ->Entidad($request->medio_pago)
                    ->get();

                    foreach ($recaudos as $key) {
                        $datos[] = array(
                            'RECAUDO ID' => $key->RecaudoId,
                            'REFERENCIA' => $key->Referencia,
                            'CEDULA' => $key->cliente->Identificacion,
                            'NOMBRE' => mb_convert_case($key->cliente->NombreBeneficiario.' '. $key->cliente->Apellidos, MB_CASE_TITLE, "UTF-8"),
                            'MUNICIPIO' => $key->cliente->municipio->NombreMunicipio,
                            'DEPARTAMENTO' => $key->cliente->municipio->NombreDepartamento,
                            'ESTADO' => $key->cliente->Status,
                            'PROYECTO' => $key->cliente->proyecto->NumeroDeProyecto,
                            'FECHA' => date('Y-m-d H:i:s', strtotime($key->Fecha)),                   
                            'VALOR' => intval($key->valor),
                            'RECAUDADO POR' => $key->RecaudadoPor,
                            'TOTAL DEUDA' => intval($key->total_deuda),
                            'USUARIO' => (empty($key->user_id))? 'SISTEMA' : $key->user->name
                        );
                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('recaudos.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos, true, 'A1', true);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }
}
