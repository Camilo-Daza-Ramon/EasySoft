<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facturacion;
use App\ClienteContrato;
use App\Proyecto;
use App\Cliente;
use App\Recaudo;
use App\ConceptoFacturacionElectronica;
use App\HistorialFacturaPagoV;
use App\Custom\Facturar;
use App\Custom\Data;
use App\Novedad;
use App\FacturaItem;
use App\FacturaNota;
use App\ContratoServicio;
use App\Departamento;

use Excel;
use DB;


class FacturacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (Auth::user()->can('facturacion-listar')) {

            $facturacion = Facturacion::selectRaw('periodo, COUNT(FacturaId) as cantidad, SUM(Internet) as valor')->groupBy('periodo')->orderBy('periodo','DESC')->paginate(15);
            return view('adminlte::facturacion.index', compact('facturacion'));
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
        
        if (Auth::user()->can('facturacion-crear')) {

             $cedulas_sin_servicio_A = Cliente::select('Clientes.Identificacion')
                ->join('clientes_contratos', function ($join) {
                    $join->on('Clientes.ClienteId', 'clientes_contratos.ClienteId')
                        ->where('clientes_contratos.estado', '=', 'VIGENTE');
                })
                ->leftJoin('contratos_servicios', 'clientes_contratos.id', 'contratos_servicios.contrato_id')
                ->where('Clientes.Status', 'ACTIVO')
                ->whereNull('contratos_servicios.contrato_id');

            $cedulas_sin_servicio_B = ContratoServicio::select('Clientes.Identificacion')
                ->join('clientes_contratos', 'contratos_servicios.contrato_id', 'clientes_contratos.id')
                ->join('Clientes', 'clientes_contratos.ClienteId', 'Clientes.ClienteId')
                ->whereIn('contratos_servicios.estado',['Pendiente','Inactivo'])
                ->where('clientes_contratos.estado', '=', 'VIGENTE')
                ->union($cedulas_sin_servicio_A)
                ->get();
                    
            
            
            //dd($cedulas_sin_servicio_B);

            $proyectos = Proyecto::get();

            return view('adminlte::facturacion.create', compact('proyectos','cedulas_sin_servicio_B'));
        }else{
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
       
        if (Auth::user()->can('facturacion-crear')) {

            $this->validate(request(),[
                'proyecto' => 'required',
                'periodo' => 'required',
                'fecha_limite_pago' => 'required',
            ]);

            $parametros = array(
                'proyecto' => $request->proyecto,
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,                
                'periodo' => $request->periodo,
                'cedulas_facturar' => $request->cedulas_facturar,
                'cedulas_no_facturar' => $request->cedulas_no_facturar,
                'clasificacion' => $request->clasificacion,
                'fecha_limite_pago' => $request->fecha_limite_pago
            );


            if ($request->periodo == date('Y-m')) {

                if(date('d') > 6 ){
                    $facturar = new Facturar;
                    $result = $facturar->generar($parametros);
                    $result = array('codigo' => 'success', 'mensaje' => 'Facturación generada correctamente.', 'periodo' => str_replace('-', '',$request->periodo));
                }else{
                    $result = array('codigo' => 'warning', 'mensaje' => 'Solo se permite facturar despues del día 6');
                }

                
            }else{
                $result = array('codigo' => 'error', 'mensaje' => 'se esta facturando un periodo que no corresponde.');
            }

            return response()->json($result);

        }else{
            abort(403);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $periodo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view($periodo, Request $request)
    {
        $periodo = $periodo;


        $facturacion = Facturacion::with('factura_electronica')
        ->where('periodo', $periodo)
        ->Buscar($request->get('palabra'))
        ->Proyecto($request->get('proyecto'))
        ->Departamento($request->get('departamento'))
        ->Municipio($request->get('municipio'))
        ->Buscarestadofe($request->get('estado'))
        ->paginate(15);

        
        $proyectos = Proyecto::select('Proyectos.ProyectoID', 'NumeroDeProyecto')
                    ->join('Clientes', 'Proyectos.ProyectoID', 'Clientes.ProyectoId')
                    ->join('Facturacion', 'Clientes.ClienteId', 'Facturacion.ClienteId')
                    ->where('Periodo', $periodo)
                    ->groupBy(['Proyectos.ProyectoID', 'NumeroDeProyecto'])->get();

        $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();



        $fact = Facturacion::select('Facturacion.FacturaId')
                ->Buscar($request->get('palabra'))
                ->Proyecto($request->get('proyecto'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))

                ->leftJoin('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')
                ->join('Clientes', 'Facturacion.ClienteId', 'Clientes.ClienteId')
                ->where('Facturacion.periodo', $periodo)
                //->where([['Facturacion.periodo', $periodo], ['ValorTotal', '>', 0], ['Internet', '>', 0]])
                ->whereNull('facturas_electronicas.FacturaId')
                ->where(function ($query) use($request) {
                    if (!empty($request->clasificacion)) {
                        $query->where('Clientes.Clasificacion', $request->clasificacion);                      
                    }
                })

                //->whereIn('Clientes.Clasificacion', ['DIALNET', 'CASMOT'])
                //->whereIn('Clientes.Identificacion', [])
                //->limit(1)
                ->whereNotIn('Clientes.ClienteId', [459918])
                //->where([['Clientes.EstadoDelServicio', 'ACTIVO'], ['Clientes.Status', 'ACTIVO']])
                ->get();

        $facturas_encero_favor = Facturacion::select('FacturaId')->Buscar($request->get('palabra'))
        ->Proyecto($request->get('proyecto'))
        ->Departamento($request->get('departamento'))
        ->Municipio($request->get('municipio'))
        ->where([['Facturacion.periodo', $periodo], ['ValorTotal', '<=', 0]])->count();


        $fact_errores = Facturacion::select('Facturacion.FacturaId')
                ->join('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')
                ->where([['Facturacion.periodo', $periodo],['facturas_electronicas.reportada',False]])
                ->get();

        if ($fact->count() == 0 || $request->get('estado') == 'false') {
            $fact = $fact_errores;
        }

        $total_errores = $fact_errores->count();

        return view('adminlte::facturacion.view', compact(
            'facturacion', 
            'periodo', 
            'fact', 
            'total_errores', 
            'proyectos', 
            'facturas_encero_favor',
            'departamentos'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $periodo
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($periodo,$id)
    {
        
        $factura = Facturacion::findOrFail($id);

        $ultimo_periodo = Facturacion::Select('Periodo')
                ->where('ClienteId', $factura->ClienteId)
                ->whereNull('estado')
                ->orderBy('Periodo', 'DESC')
                ->groupBy('Periodo')
                ->limit(3)
                ->first();

        $tipos_conceptos_debito = json_encode(ConceptoFacturacionElectronica::select('id','nombre')
        ->where('tipo','CONCEPTO NOTA DEBITO')
        ->get());

        $tipos_conceptos_credito = json_encode(ConceptoFacturacionElectronica::select('id','nombre')
        ->where('tipo','CONCEPTO NOTA CREDITO')
        ->get());

        $tipos_negociacion = ConceptoFacturacionElectronica::select('id','nombre')
        ->where('tipo','TIPO DE NEGOCIACION')
        ->get();

        $medios_pago = ConceptoFacturacionElectronica::select('id','nombre')
        ->where('tipo','MEDIO DE PAGO')
        ->get();

        $tipos_operacion = ConceptoFacturacionElectronica::select('id','nombre')
        ->where('tipo','TIPO DE OPERACION')
        ->get();

        return view('adminlte::facturacion.show', compact('factura', 'medios_pago','tipos_operacion','tipos_negociacion','tipos_conceptos_debito', 'tipos_conceptos_credito','ultimo_periodo'));
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
        if (Auth::user()->can('facturacion-eliminar')) {

            $factura = Facturacion::findOrFail($id);
            $periodo = $factura->Periodo;

            $result = DB::transaction(function () use($factura) {

                foreach ($factura->item as $item) {
                    if (!empty($item)) {
                        if(!$item->delete()){
                            DB::rollBack();
                            return ['error', 'Error al eliminar el item de la factura. ' . $item->concepto];
                        }

                    }
                }

                foreach ($factura->factura_novedad as $novedad) {

                    if (!empty($novedad)) {
                        $novedades = Novedad::find($novedad->novedad_id);
                        $novedades->estado = 'PENDIENTE';
                        if(!$novedades->save()){
                            DB::rollBack();
                            return ['error', 'Error al actualizar el estado de la novedad. ' . $novedad->novedad_id];
                        }
                    }            
                }

                $factura_electronica = $factura->factura_electronica;

                if (!empty($factura_electronica)) {

                    if ($factura_electronica->reportada && !empty($factura_electronica->archivo)) {
                        DB::rollBack();
                        return ['error', 'No es posible eliminar porque ya tiene una facturacion electronica asociada.'];

                    }else{
                        foreach ($factura_electronica->detalles_factura_electronica as $detalles) {
                            if (!empty($detalles)) {
                                if(!$detalles->delete()){
                                    DB::rollBack();
                                    return ['error', 'Error al eliminar el detalle de la factura electronica. ' . $detalles->id];
                                }

                            }
                        }

                        if(!$factura_electronica->delete()){
                            DB::rollBack();
                            return ['error', 'Error al eliminar la factura electronica'];
                        }
                    }
                }

                if($factura->delete()){
                    return ['success', 'Factura eliminada correctamente'];
                }else{
                    DB::rollBack();
                    return ['error', 'No se pudo eliminar la factura.'];                
                }
            });

            return redirect()->route('facturacion.view', $periodo)->with($result[0], $result[1]);
        }else{
            abort(403);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportar(Request $request)
    {

        $detalles = array();#TRANSLADOS - INSTALACION - RECONEXION - DIAS SIN SERVICIO - SALDO EN MORA
        $otros_detalles = array();
        $anticipos = array();
        $informacion_adicional = "";
        $datos_adiciones = array();
        $porcentaje_descuento = null;
        $motivo_descuento = null;
        $factura = Facturacion::findOrFail($request->factura_id);

        $datos_adiciones['Campo1'] = $factura->plan_contratado;
        $datos_adiciones['Campo2'] = $factura->descripcion_plan;
        $datos_adiciones['Campo3'] = $factura->tipo_facturacion;

        $periodo_facturado = $factura->PeriodoFacturado;
        
        $fecha_inicio_facturacion = substr($factura->PeriodoServicio, 0,4). '-' . substr($factura->PeriodoServicio, 4,2) . '-01';
        $fecha_fin_facturacion = date("Y-m-t", strtotime($fecha_inicio_facturacion));

        $facturacion_api = $factura->proyecto->facturacion_api;
        

        $valor_total_pagar = 0;
        $reportar= true;

        $ciudad = "";
        $departamento = "";

        if (!empty($factura->cliente->municipio)) {
            $ciudad = $factura->cliente->municipio->CodigoDaneMunicipio;
            $departamento = $factura->cliente->municipio->departamento->CodigoDaneDepartamento;            
        }else{
            $ciudad = $factura->cliente->ubicacion->municipio->CodigoDaneMunicipio;
            $departamento = $factura->cliente->ubicacion->municipio->departamento->CodigoDaneDepartamento;            
        }

        if (strlen($ciudad) < 3){
            for ($i=strlen($ciudad); $i <= 3; $i++) { 
                $ciudad = '0' . $ciudad;
                $i+=1;
            }
        }

        $data = new Data;

        #Codigo nueva facturación
        $texto_compensacion = "";

        $total_descuento = 0;
        $total_mora = 0;

        $saldo_favor = 0;

        $h = 0;
        $filas = '';


        foreach ($factura->item as $item) {

            $h+=1;

            $filas .= '<tr>
                        <td>'.$h.'</td>
                        <td>'.$item->concepto.'</td>
                        <td style="text-align: right;">'.$item->cantidad.'</td>
                        <td style="text-align: right;">$'.number_format($item->valor_unidad, 2, '.', ',').'</td>
                        <td style="text-align: right;">$'.number_format($item->valor_iva, 2, '.', ',').'</td>
                        <td style="text-align: right;">$'.number_format($item->valor_total, 2, '.', ',').'</td>
                    </tr>';

            if (substr($item->concepto, 0,13) == "Compensación") {
                $texto_compensacion = "<br>" ."El tiempo de Compensación por indisponibilidad está estimado en ".$item->unidad_medida . "<br>";
            }

            if ($item->concepto == "Saldo en Mora") {
                $otros_detalles[] = $data->facturaAddDetalles
                (
                    $item->concepto, 
                    $item->cantidad,
                    $item->valor_unidad,
                    $item->valor_iva,
                    $item->valor_total,
                    $item->iva,
                    $item->valor_iva
                );

                $total_mora = $item->valor_total;
                continue;

            }else if ($item->valor_total <= 0){
                $otros_detalles[] = $data->facturaAddDetalles
                (
                    $item->concepto, 
                    $item->cantidad,
                    $item->valor_unidad,
                    $item->valor_iva,
                    $item->valor_total,
                    $item->iva,
                    $item->valor_iva
                );

                //$informacion_adicional .= $item->concepto ." $". number_format($item->valor_total, 2, ',', '.') . "<br>";

                if ($item->concepto != 'Saldo a Favor') {
                    $total_descuento += $item->valor_total;

                }

                if ($item->concepto == 'Saldo a Favor') {
                    $saldo_favor = $item->valor_total;
                }

                
            }else{
                $detalles[] = $data->facturaAddDetalles
                (
                    $item->concepto, 
                    $item->cantidad,
                    $item->valor_unidad,
                    $item->valor_iva,
                    $item->valor_total,
                    $item->iva,
                    $item->valor_iva
                );

                $valor_total_pagar =  $valor_total_pagar + ($item->valor_total);
            }
        }

        if(($valor_total_pagar + ($total_descuento)) <= 0){
            $porcentaje_descuento = 100;
            $motivo_descuento = 'Otros descuentos*';
        }else{

            $descjj = array();

            foreach ($detalles as $key => $value) {                               

                if($total_descuento < 0){

                    $valor_original = $value['Valor'];

                    if($value['Valor'] > 0  && (($value['Valor'] + ($total_descuento)) > 0) && $value['TotalImpuestos'] == 0){                        

                        if(($value['Total'] - $value['Valor']) == 0){

                            $value['Valor'] = $value['Valor'] + ($total_descuento);
                            $value['Total'] = $value['Total'] + ($total_descuento);

                        }else{
                            continue;
                        }
                        

                        //Solución inconpleta debido a que no se puede afectar el valor correspondiente a TotalImpuestos del detalle de la factura
                        //esta opcion no es recomendada debido a que al aplicar un descuento sobre un item con impuesto, el valor a cobrar se reduce.
                        /*if($value['TotalImpuestos'] > 0){

                            $impuesto = $value['Valor'] * 0.19;
                            
                            $value['TotalImpuestos'] = $impuesto;
                            $value['Impuestos'][0]['Total'] = $impuesto;

                            $value['Total'] = $value['Valor'] + $impuesto;

                            $factura->Iva = $impuesto;

                            //faltó descontar sobre el valor total de la factura
                        }*/                       

                        
                    }else if($value['TotalImpuestos'] == 0 && (($value['Valor'] + ($total_descuento)) > 0) ){

                        $value['Valor'] = $value['Valor'] + ($total_descuento);
                        $value['Total'] = $value['Total'] + ($total_descuento);

                        
                    }else{

                        continue;
                    }

                    $valor_total_pagar = $valor_total_pagar + ($total_descuento);
                    $total_descuento += $valor_original;
                    
                }

                unset($detalles[$key]); //Elimina el array


                if($value['Total'] > 0){
                    $detalles[$key] = $value;
                }
                
            }

            //dd($detalles);

            // Elimina los valores nulos o vacíos si es necesario
            $detalles = array_filter($detalles);

            // Reindexa el array
            $detalles = array_values($detalles);

        }
       

        $telefono = "";

        if (!empty($factura->cliente->TelefonoDeContactoMovil) && $factura->cliente->TelefonoDeContactoMovil != "") {
           $telefono = $factura->cliente->TelefonoDeContactoMovil;

        }elseif (!empty($factura->cliente->TelefonoDeContactoFijo)) {
            $telefono = $factura->cliente->TelefonoDeContactoFijo;
        }

        $direccion = "";

        if (!empty($factura->cliente->DireccionDeCorrespondencia) && $factura->cliente->DireccionDeCorrespondencia != "") {
           $direccion = $factura->cliente->DireccionDeCorrespondencia;

        }elseif (!empty($factura->cliente->DireccionNomenclatura)) {
            $direccion = $factura->cliente->DireccionNomenclatura;
        }
        
        $informacion_adicional2 = '<table width="100%">
        <thead>
            <tr>
                <th style="text-align: left;">Nro</th>
                <th style="text-align: left;">Descripción</th>
                <th style="text-align: right;">Cant.</th>
                <th style="text-align: right;">Vr. Unitario</th>
                <th style="text-align: right;">IVA</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>'. $filas.
        '</tbody>
        <tfoot>
            <tr style="font-size:14px;font-weight: bold;">
                <td colspan="5" style="border-top: solid black; border-bottom: solid black;">Valor Total a Pagar:</td>
                <td style="border-top: solid black; border-bottom: solid black; text-align: right;">$'.number_format($factura->ValorTotal, 2, '.', ',').'</td>
            </tr>
        </tfoot>
        </table>';

    $informacion_suspendidos = '<p style="text-align:center;">
            <span style="background-color:rgba(251, 99, 99, 0.779); font-size:12px; font-weight:bold; padding: 3px; border-radius:5px;">Fecha de Suspencion del servicio: '.date("t/m/Y").'</span>
            <br> Recuerde que al suspender sus servicios se aplicarán cargos adicionales por concepto
            de reconexión.
        <p>';

    if(intval($factura->SaldoEnMora) > 100){

    }else{
        $informacion_adicional2 .= $informacion_suspendidos;
    }

    $informacion_adicional2 = str_replace("\n", "",$informacion_adicional2);

        $datos_adiciones['Campo4'] = $periodo_facturado;
        $datos_adiciones['Campo5'] = $fecha_inicio_facturacion;
        $datos_adiciones['Campo6'] = $fecha_fin_facturacion;
        $datos_adiciones['Campo7'] = $fecha_fin_facturacion;
        //$datos_adiciones['CAMPO8'] = '$' . number_format($factura->ultimo_pago,0, ',','.');
        //$datos_adiciones['CAMPO9'] = $factura->fecha_ultimo_pago;
        $datos_adiciones['Campo10'] = (intval($factura->SaldoEnMora) > 100)? 'INMEDIATO': date_format(date_create($factura->FechaDePago),'Y-m-d');

        $fecha_vencimiento = (intval($factura->SaldoEnMora) > 100)? date('Y-m-d H:i:s'): date_format(date_create($factura->FechaDePago),'Y-m-d H:i:s');

        $factura_reporte = $data->factura_electronica(
            1,//tipo de documento
            '',//tipo_concepto.
            '10',//tipo de operacion
            '1',//tipo de negociacion
            '1',//tipo de medio de pago
            $factura->FacturaId,//documento_relacionado
            $fecha_vencimiento,//fecha_vencimiento
            $valor_total_pagar,//$factura->ValorTotal,//valor_total
            $factura->Iva,//total_impuestos
            $informacion_adicional2 . $texto_compensacion,//informacion Adicional
            $departamento,//departamento
            $ciudad,//ciudad
            $factura->cliente->Identificacion,//cedula
            $factura->cliente->NombreBeneficiario,//nombres
            $factura->cliente->Apellidos,//apellidos
            $factura->cliente->CorreoElectronico,//correo
            $telefono,//telefono
            $direccion,//direccion
            $detalles,//detalles
            $datos_adiciones,
            $porcentaje_descuento,
            $motivo_descuento,
            $factura->FechaEmision
        );

    	



        return response()->json(array('datosjson' => $factura_reporte, 'api' => $facturacion_api, 'reportar' => $reportar));
        
    }

    

    public function exportar(Request $request){

        if (Auth::user()->can('facturacion-exportar')) {
            $periodo = $request->periodo;

            Excel::create('Facturacion_' . $periodo, function($excel) use($periodo, $request) {
                

                if ($request->tipo == "SOFTV") {
                   
                   $excel->sheet('Facturas', function($sheet) use($periodo) {

                        $datos = array();
                        $datos1 = array();
                        
                        $facturas_primer = Facturacion::selectRaw(
                            'Clientes.Identificacion,
                            Clientes.Municipio,
                            Facturacion.FacturaId,
                            Facturacion.Periodo,
                            Facturacion.Internet,
                            Facturacion.ValorTotal,
                            Facturacion.Mes,
                            Facturacion.Año,
                            Facturacion.FechaEmision,
                            Facturacion.PeriodoFacturado, 
                            facturas_electronicas.numero_factura_dian, 
                            CAST(facturas_electronicas.archivo AS NVARCHAR(MAX)) as archivo')
                        ->join('Clientes', 'Clientes.ClienteId', '=', 'Facturacion.ClienteId')
                        ->join('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')
                        ->join('metas_clientes','Clientes.ClienteId', '=','metas_clientes.ClienteId')
                        ->leftJoin('clientes_reemplazos', 'metas_clientes.id', '=', 'clientes_reemplazos.meta_cliente_id')
                        ->where([
                            ['Facturacion.Periodo', $periodo], 
                            ['Facturacion.ProyectoId', 6]
                        ])
                        ->whereNull('clientes_reemplazos.meta_cliente_id')
                        ->whereNull('Facturacion.estado');


                        $facturas = Facturacion::selectRaw(
                            'Clientes.Identificacion,
                            Clientes.Municipio, 
                            Facturacion.FacturaId,
                            Facturacion.Periodo,
                            Facturacion.Internet,
                            Facturacion.ValorTotal,
                            Facturacion.Mes,
                            Facturacion.Año,
                            Facturacion.FechaEmision,
                            Facturacion.PeriodoFacturado, 
                            facturas_electronicas.numero_factura_dian, 
                            CAST(facturas_electronicas.archivo AS NVARCHAR(MAX)) as archivo')
                        ->join('Clientes', 'Clientes.ClienteId', '=', 'Facturacion.ClienteId')
                        ->join('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')                        
                        ->join('clientes_reemplazos', 'Clientes.ClienteId', '=', 'clientes_reemplazos.cliente_nuevo_id')
                        ->where([
                            ['Facturacion.Periodo', $periodo], 
                            ['Facturacion.ProyectoId', 6]
                        ])
                        ->whereNull('Facturacion.estado')
                        ->union($facturas_primer)
                        ->get();

                        foreach ($facturas as $key) {
                            $datos[] = array(

                                'Identificacion' => $key->Identificacion,
                                'Municipio' => $key->Municipio,
                                'FacturaId' => $key->FacturaId,
                                'Periodo' => intval($key->Periodo),
                                'Internet' => floatval($key->Internet),
                                'ValorTotal' => floatval($key->ValorTotal),
                                'Mes' => $key->Mes,
                                'Año' => $key->Año,
                                'FechaEmision' => $key->FechaEmision,
                                'PeriodoFacturado' => $key->PeriodoFacturado,
                                'numero_factura_dian' => $key->numero_factura_dian,
                                'archivo' => $key->archivo
                            );
                        }


                        if (count($datos) == 0) {
                            return redirect()->route('facturacion.index')->with('warning', 'No hay datos para el filtro enviado.');
                        } 

                        $sheet->fromArray($datos, true, 'A1', true);
                    });

                }else{

                    $facturas = Facturacion::selectRaw("Facturacion.FacturaId, Clientes.Identificacion,  Clientes.NombreBeneficiario, Clientes.Apellidos, Clientes.TelefonoDeContactoFijo as telefono, Clientes.TelefonoDeContactoMovil as celular,  Clientes.CorreoElectronico, Clientes.DireccionDeCorrespondencia, Clientes.Estrato, Clientes.Barrio, Municipios.NombreMunicipio, Municipios.CodigoDane as dane_municipio, Municipios.NombreDepartamento, Departamentos.CodigoDaneDepartamento, Clientes.Status as estado, Clientes.EstadoDelServicio, Proyectos.NumeroDeProyecto, metas.nombre as meta, metas_clientes.idpunto, Clientes.tipo_beneficiario, ISNULL(Facturacion.descripcion_plan,PlanesComerciales.DescripcionPlan) AS descripcion_plan, Clientes.TarifaInternet, Facturacion.Periodo, Facturacion.PeriodoFacturado, Facturacion.Internet, Facturacion.Iva, Facturacion.saldo_favor, Facturacion.SaldoEnMora, Facturacion.ultimo_pago as Pago_Anterior, Facturacion.ValorTotal, facturas_electronicas.numero_factura_dian, Facturacion.PeriodoServicio, Clientes.Clasificacion, Facturacion.estado as estado_factura,facturas_electronicas.archivo, Facturacion.FechaEmision, Facturacion.tipo_facturacion")
                    ->Buscar($request->palabra)
                    ->Proyecto($request->proyecto)
                    ->Departamento($request->departamento)
                    ->Municipio($request->municipio)
                    ->leftJoin('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')                        
                    ->join('Clientes', 'Clientes.ClienteId', '=', 'Facturacion.ClienteId')                
                    ->leftJoin('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->leftJoin('Departamentos', 'Municipios.DeptId', '=', 'Departamentos.DeptId')
                    ->join('PlanesComerciales', 'PlanesComerciales.PlanId', '=', 'Clientes.PlanComercial')
                    ->join('Proyectos', 'Proyectos.ProyectoID', '=', 'Facturacion.ProyectoId')
                    ->leftJoin('metas_clientes','Clientes.ClienteId', '=','metas_clientes.ClienteId')
                    ->leftjoin('metas', 'metas_clientes.meta_id','=','metas.id')
                    ->where('Facturacion.Periodo', $periodo)
                    ->get();

                    $items = Facturacion::selectRaw('Facturacion.FacturaId, Facturacion.ClienteId, Facturacion.ProyectoId,Facturacion.estado, fi.*')
                    ->Buscar($request->palabra)
                    ->Proyecto($request->proyecto)
                    ->Departamento($request->departamento)
                    ->Municipio($request->municipio)
                    ->join('facturas_items as fi', 'fi.factura_id', '=', 'Facturacion.FacturaId')
                    ->where('Facturacion.Periodo', $periodo)
                    ->get();

                    $excel->sheet('Facturas', function($sheet) use($facturas) {

                        $datos = array(); 

                        foreach ($facturas as $key) {

                            $fecha_inicio_facturacion = substr($key->PeriodoServicio, 0,4). '-' . substr($key->PeriodoServicio, 4,2) . '-01';
                            $fecha_fin_facturacion = date("Y-m-t", strtotime($fecha_inicio_facturacion));

                            
                            $total_notas_credito = FacturaNota::selectRaw('SUM(valor_total - (valor_total * (descuento/100))) as total')->where([['tipo_nota', 'CREDITO'],['factura_id', $key->FacturaId]])->groupBy('factura_id')->first();

                            $descuentos = floatval($key->item->where('valor_total','<', 0)->whereNotIn('concepto', 'Saldo a Favor')->sum('valor_total'));

                            $facturado =  floatval($key->item->where('valor_total','>', 0)->sum('valor_total'));


                            $datos[] = array(
                                'FACTURA ID' => $key->FacturaId,
                                'CEDULA' => $key->Identificacion,
                                'NOMBRE' => mb_convert_case($key->NombreBeneficiario.' '. $key->Apellidos, MB_CASE_TITLE, "UTF-8"),
                                'TELEFONO' => $key->telefono,
                                'CELULAR' => $key->celular,
                                'CORREO' => strtolower($key->CorreoElectronico),
                                'DIRECCION' => $key->DireccionDeCorrespondencia,
                                'ESTRATO' => $key->Estrato,
                                'BARRIO' => $key->Barrio,
                                'MUNICIPIO' => $key->NombreMunicipio,
                                'DANE-MUNICIPIO' => $key->dane_municipio,
                                'DEPARTAMENTO' => $key->NombreDepartamento,
                                'DANE-DEPARTAMENTO' => $key->CodigoDaneDepartamento,
                                'ESTADO FACTURA' => $key->estado_factura,
                                'ESTADO DEL CLIENTE' => $key->estado,
                                'ESTADO DEL SERVICIO' => $key->EstadoDelServicio,
                                'PROYECTO' => $key->NumeroDeProyecto,
                                'META' => $key->meta,
                                'ID-PUNTO' => $key->idpunto,
                                'TIPO-BENEFICIARIO' => $key->tipo_beneficiario,
                                'PLAN COMERCIAL' => $key->descripcion_plan,
                                'TIPO FACTURACION' => $key->tipo_facturacion,
                                'TARIFA' => intval($key->TarifaInternet),
                                'PERIODO' => intval($key->Periodo),
                                'PERIODO FACTURADO' => strtoupper(strftime("%B %Y",strtotime(substr($key->PeriodoServicio,0,4) .'-'. substr($key->PeriodoServicio,4)))),
                                'FECHA-EXPEDICION' => $key->FechaEmision,
                                'FECHA-INICIO' => $fecha_inicio_facturacion,
                                'FECHA-FIN' => $fecha_fin_facturacion,
                                'VALOR INTERNET' => intval($key->Internet),
                                //'VALOR TV' => intval($key->Tv),
                                //'OTRO' => intval($key->Otro),
                                'IVA' => floatval($key->Iva),
                                'SALDO A FAVOR' => floatval($key->saldo_favor),
                                'SALDO EN MORA PARA EL PERIODO' => floatval($key->SaldoEnMora),
                                //'HORAS SIN SERVICIO' => intval($key->HorasSinServicio),
                                'PAGO ANTERIOR' => floatval($key->Pago_Anterior),
                                //'TRASLADO' => intval($key->Traslado),
                                //'VALOR CUOTA' => intval($key->ValorCuota),
                                //'AJUSTES POR FALTA DEL SERVICIO' => intval($key->AjustesPorFaltaDeServicio),
                                'DESCUENTOS' => $descuentos,
                                'FACTURADO' => floatval($key->item->where('valor_total','<', 0)->whereNotIn('concepto', 'Saldo a Favor')->sum('valor_total')),
                                'VALOR TOTAL' => floatval($key->ValorTotal),
                                'DIAN' => floatval((($facturado + $descuentos) - $key->SaldoEnMora < 0)? 0 : ($facturado + $descuentos) - $key->SaldoEnMora ),
                                'NOTAS CREDITO' => floatval((!empty($total_notas_credito))? $total_notas_credito->total : 0 ),
                                'NOTAS DEBITO' => floatval($key->nota->where('tipo_nota', 'DEBITO')->sum('valor_total')),
                                //'TOTAL DEUDA A LA FECHA' => intval($key->total_deuda),                            
                                'FACTURA DIAN' => $key->numero_factura_dian,
                                'CLASIFICACION' => $key->Clasificacion,
                                'ARCHIVO' => $key->archivo
                            );
                        }


                        if (count($datos) == 0) {
                            return redirect()->route('facturacion.index')->with('warning', 'No hay datos para el filtro enviado.');
                        } 

                        $sheet->fromArray($datos, true, 'A1', true);
                    });

                    $excel->sheet('Items Facturados', function($sheet) use($items){

                        $datos1 = array();

                        foreach ($items as $item) {
                            $datos1[] = array(
                                'CEDULA' => $item->cliente->Identificacion,
                                'FACTURA ID' => $item->factura_id,
                                'ESTADO FACTURA' => $item->estado,
                                'MUNICIPIO' => $item->cliente->municipio->NombreMunicipio,
                                'DEPARTAMENTO' => $item->cliente->municipio->NombreDelDepartamento,
                                'PROYECTO' => $item->proyecto->NumeroDeProyecto,
                                'CONCEPTO' => $item->concepto,
                                'CANTIDAD' => floatval($item->cantidad),
                                'VALOR/UNIDAD' => floatval($item->valor_unidad),
                                'IVA' => (int) $item->iva,
                                'VALOR IVA' => floatval($item->valor_iva),
                                'TOTAL' => floatval($item->valor_total)
                            );
                        }

                        if (!empty($datos1)) {
                            $sheet->fromArray($datos1, true, 'A1', true);
                        }
                    });
                }

                
            })->export('xlsx');
        }else{
            abort(403);
        }

    }

    public function ajax(Request $request){
         if ($request->ajax()) {
           
            /*$request->periodo = str_replace('-', '', $request->periodo);

            $cliente = Cliente::select('Clientes.ClienteId','Clientes.NombreBeneficiario', 'Clientes.Apellidos', 'Clientes.TelefonoDeContactoFijo', 'Clientes.TelefonoDeContactoMovil', 'Clientes.DireccionDeCorrespondencia', 'Clientes.CorreoElectronico', 'Clientes.Estrato', 'PlanesComerciales.nombre', 'PlanesComerciales.DescripcionPlan','PlanesComerciales.ValorDelServicio' , 'PlanesComerciales.Iva' ,'historial_factura_pagoV.total_deuda', 'Proyectos.NumeroDeProyecto')
                        ->leftJoin('Facturacion', function ($join) use ($request){
                            $join->on('Clientes.ClienteId', '=', 'Facturacion.ClienteId')
                                ->where('Facturacion.Periodo', $request->periodo);
                            })
                        ->join('PlanesComerciales','Clientes.PlanComercial', 'PlanesComerciales.PlanId')
                        ->leftJoin('historial_factura_pagoV', 'Clientes.ClienteId', 'historial_factura_pagoV.ClienteId')
                        ->join('Proyectos', 'Clientes.ProyectoId', 'Proyectos.ProyectoID')
                        ->where('Clientes.Identificacion', $request->documento)
                        ->whereNull('Facturacion.ClienteId')
                        ->get();

            if ($cliente->count() > 0) {
               $recaudo = Recaudo::selectRaw('sum(valor) as valor')->where([['ClienteId', $cliente[0]->ClienteId], ['Periodo', date("Ym",strtotime($request->periodo."- 1 month"))]])->get();
            }else{
                $recaudo = null;
            }

            return response()->json(array('cliente' => $cliente, 'recaudo' => $recaudo));*/
            #tener en cuenta para julio
            #46647391 
            $result = array();

            /*if ($request->periodo == date('Y-m')) {
                $facturar = new Facturar;
                $result = $facturar->generar($request->periodo);
            }else{
                $result = ['se esta facturando un periodo que no corresponde.'];
            }*/

            return response()->json($result);
        }  
    }

    
}
