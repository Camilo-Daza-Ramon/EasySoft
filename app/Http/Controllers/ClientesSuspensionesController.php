<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ClienteSuspension;
use App\Novedad;
use App\Proyecto;
use App\Recaudo;
use App\Departamento;
use Excel;
use DB;

class ClientesSuspensionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('clientes-suspensiones-listar')) {

            $suspendidos = Novedad::Cedula($request->get('documento'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->Fechas($request->get('fecha_inicio'), $request->get('fecha_fin'))
            ->where([
                ['concepto', 'Suspensión por mora']
            ])->whereNull('fecha_fin')
            ->orderBy('fecha_inicio','ASC')
            ->paginate(15);

            //$suspendidos = ClienteSuspension::orderBy('fecha_inicio','ASC')->paginate(15);
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            return view('adminlte::clientes.suspensiones.index',compact('suspendidos','proyectos', 'departamentos'));
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
        if (Auth::user()->can('clientes-suspensiones-crear')) {

            $this->validate(request(),[
                'tipo' => 'required',
                'cedulas' => 'required'                 
            ]);
            

            $result = DB::transaction(function () use($request) {


            });


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
        //
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
        //
    }

    public function exportar(Request $request){

        if (Auth::user()->can('clientes-suspensiones-exportar')) {

            Excel::create('clientes-suspendidos', function($excel) use($request) {
     
                $excel->sheet('Suspendidos', function($sheet) use($request) {

                    $datos = array();

                    $suspendidos = Novedad::Cedula($request->documento)
                        ->Proyecto($request->proyecto)
                        ->Departamento($request->departamento)
                        ->Municipio($request->municipio)
                        ->Fechas($request->get('fecha_inicio'), $request->get('fecha_fin'))
                        ->where([
                            ['concepto', 'Suspensión por mora']
                        ])->whereNull('fecha_fin')
                        ->get();

                    

                    foreach ($suspendidos as $suspendido) {

                        $contador = date_diff(date_create($suspendido->fecha_inicio), date_create(date('Y-m-d H:i:s')));
                        $total_dias = $contador->format('%a');

                        $pago = Recaudo::select('valor','Fecha')
                        ->where('ClienteId', $suspendido->cliente_id)       
                        ->orderBy('RecaudoId','DESC')
                        ->first();

                        

                         $datos[] = array(
                            'CEDULA' => $suspendido->cliente->Identificacion,                                                       
                            'NOMBRE' => $suspendido->cliente->NombreBeneficiario,
                            'APELLIDO' => $suspendido->cliente->Apellidos,
                            'PROYECTO' => $suspendido->cliente->proyecto->NumeroDeProyecto, 
                            'TELEFONO' => $suspendido->cliente->TelefonoDeContactoFijo,
                            'CELULAR' => $suspendido->cliente->TelefonoDeContactoMovil,
                            'MUNICIPIO' => $suspendido->cliente->municipio->NombreMunicipio,
                            'DEPARTAMENTO' => $suspendido->cliente->municipio->NombreDepartamento,
                            'ESTADO CLIENTE' => $suspendido->cliente->Status,
                            'ESTADO SERVICIO' => $suspendido->cliente->EstadoDelServicio,
                            'FECHA INICIO SUSPENSION' => $suspendido->fecha_inicio,
                            'DIAS SUSPENDIDO' => intval($contador->format('%a')),
                            'PLAN COMERCIAL' => $suspendido->cliente->plancomercial->nombre,
                            'PLAN COMERCIAL - VALOR' => floatval($suspendido->cliente->plancomercial->ValorDelServicio),

                            'TOTAL DEUDA' => floatval((isset($suspendido->cliente->historial_factura_pago))? $suspendido->cliente->historial_factura_pago->total_deuda : 0),
                            'ULTIMO PERIODO FACTURADO' => (isset($suspendido->cliente->ultima_factura))? $suspendido->cliente->ultima_factura->factura->PeriodoFacturado : '',
                            'VALOR ULTIMO PAGO' => (isset($pago)? floatval($pago->valor) : ''),
                            'FECHA ULTIMO PAGO' => (isset($pago)? $pago->Fecha : ''),
                        );

                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('clientes-suspensiones.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos, true, 'A1', true);

                    //$sheet->fromArray($datos);
     
                });
            })->export('xlsx');

        }else{
            abort(403);
        }

    }
}
