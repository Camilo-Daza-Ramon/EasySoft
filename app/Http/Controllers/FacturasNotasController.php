<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\Data;
use App\FacturaNota;
use App\NotaProducto;
use App\NotaResultadoFeel;
use App\Facturacion;
use App\Novedad;
use App\FacturaNovedad;
use App\Proyecto;
use DB;
use Excel;

class FacturasNotasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('facturacion-notas-listar')) {

            $notas = FacturaNota::
            Tipo($request->get('tipo_nota'))
            ->Cedula($request->get('documento'))
            ->Proyecto($request->get('proyecto'))
            ->Periodo($request->get('periodo'))
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            return view('adminlte::facturacion.notas.index', compact('notas', 'proyectos'));

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
        if (Auth::user()->can('facturacion-notas-crear')) {
            $this->validate(request(),[
                'tipo_nota' => 'required',            
                'tipo_concepto' => 'required',
                'tipo_operacion' => 'required',
                'tipo_negociacion' => 'required',
                'tipo_medio_pago' => 'required',
                'total' => 'required',
                'factura_id' => 'required',
                'conceptos' => 'required',
                'anular' => 'required']
            );

            $result = DB::transaction(function () use($request) {

                $reportar = $request->reportar;

                $nota = new FacturaNota;
                $nota->tipo_nota = $request->tipo_nota;
                $nota->tipo_concepto_id = $request->tipo_concepto;
                $nota->tipo_operacion_id = $request->tipo_operacion;
                $nota->tipo_negociacion_id = $request->tipo_negociacion;
                $nota->tipo_medio_pago_id = $request->tipo_medio_pago;            
                //$nota->fecha_expedision = date('Y-m-d h:i:s');
                $nota->fecha_expedision = date('Y-m-d H:i:s');
                $nota->factura_id = $request->factura_id;
                $nota->valor_total = $request->total;

                

            
                $nota->descuento = (!empty($request->descuento)) ? $request->descuento : 0;
                $nota->motivo_descuento = $request->motivo_descuento;
                

                if ($nota->save()) {
                    foreach ($request->conceptos as $concepto) {
                        $producto = new NotaProducto;
                        $producto->concepto = $concepto['concepto'];
                        $producto->cantidad = $concepto['cantidad'];
                        $producto->valor_unidad = $concepto['valor_unidad'];
                        $producto->iva = $concepto['iva'];
                        $producto->valor_iva = $concepto['valor_iva'];
                        $producto->valor_total = $concepto['total'];
                        $producto->factura_nota_id = $nota->id;

                        if (!$producto->save()) {
                            DB::rollBack();
                        }
                    }

                    if ($request->anular == 'SI') {
                        $factura = Facturacion::find($request->factura_id);
                        $factura->estado = 'ANULADA';
                        if (!$factura->save()) {
                            DB::rollBack();
                        }else{
                            foreach ($factura->factura_novedad as $novedad) {

                                if (!empty($novedad)) {
                                    $novedades = Novedad::find($novedad->novedad_id);
                                    $novedades->estado = 'PENDIENTE';
                                    if (!$novedades->save()) {
                                        DB::rollBack();
                                    }else{

                                        $factura_novedad = FacturaNovedad::where([
                                            ['factura_id', $request->factura_id],
                                            ['novedad_id', $novedad->novedad_id]
                                        ])->delete();

                                        if (!$factura_novedad) {                                            
                                            DB::rollBack();                                            
                                        }
                                    }
                                }
                                
                            }
                        }
                    }

                    

                    if ($reportar == 'on') {
                        $data = $this->notaElectronica($nota->id);
                        return ['nota_electronica' => $data['datosjson'], 'api' => $data['api'], 'respuesta' => 'reportar', 'reportar' => true, 'nota_id' => $nota->id];
                    }else{
                        return ['nota_electronica' => null, 'respuesta' => 'Nota creada satisfactoriamente!', 'reportar' => false];
                    }
                }else{
                    DB::rollBack();
                }
            });

            return response()->json($result);
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

        if (Auth::user()->can('facturacion-notas-ver')) {
            $dato = FacturaNota::with('producto')->findOrFail($id);

            $nota = array();

            $nota['productos'] = $dato->producto;
            
            $array = array();
            $array['id'] = $dato->id;
            $array['tipo_nota'] = $dato->tipo_nota;
            $array['tipo_concepto'] = $dato->tipo_concepto->nombre;
            $array['tipo_operacion'] = $dato->tipo_operacion->nombre;
            $array['tipo_negociacion'] = $dato->tipo_negociacion->nombre;
            $array['tipo_medio_pago'] = $dato->tipo_medio_pago->nombre;
            $array['fecha_expedision'] = $dato->fecha_expedision;
            $array['reportada'] = $dato->reportada;
            $array['numero_nota_dian'] = $dato->numero_nota_dian;
            $array['documento_id_feel'] = $dato->documento_id_feel;
            $array['valor_total'] = $dato->valor_total;
            $array['descuento'] = intval($dato->descuento);
            $array['motivo_descuento'] = $dato->motivo_descuento;

            $nota['datos'] = $array;
            


            return response()->json($nota);
        }else{
            abort(403);
        }

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
        if (Auth::user()->can('facturacion-notas-actualizar')) {
            $nota = FacturaNota::find($id);
            $nota->reportada = $request->reportada;
            $nota->numero_nota_dian = $request->numero_nota_dian;
            $nota->documento_id_feel = $request->documento_id_feel;
            $nota->archivo = $request->archivo;

            if($nota->save()){
                return response()->json(array('result'=> true));
            }else{
                return response()->json(array('result'=> false));
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
        if (Auth::user()->can('facturacion-notas-eliminar')) {
            $nota = FacturaNota::findOrFail($id);
            $factura_id = $nota->factura_id;
            $periodo = $nota->factura->Periodo;

            if ($nota->reportada) {
                return redirect()->route('facturacion.show', [$periodo,$factura_id])->with('error', 'La nota no puede ser eliminada porque ya esta reportada en la DIAN.');
            }else{
                $productos = $nota->producto();
                $productos->delete();

                if ($nota->delete()) {

                    if ($nota->tipo_concepto == 'Anulación de factura electrónica') {
                        $factura = Facturacion::find($factura_id);
                        $factura->estado = null;
                        if ($factura->save()){
                            return redirect()->route('facturacion.show', [$periodo,$factura_id])->with('success', 'La nota se eliminó correctamente.');
                        }else{
                            return redirect()->route('facturacion.show', [$periodo,$factura_id])->with('error', 'error al actualizar el estado de la factura.');
                        }
                    }else{
                        return redirect()->route('facturacion.show', [$periodo,$factura_id])->with('success', 'La nota se eliminó correctamente.');
                    }
                    
                }
            }
        }else{
            abort(403);
        }   

    }

    public function detallesFeel(Request $request){
        
        $notaE_detalles = new NotaResultadoFeel();        
        $notaE_detalles->factura_nota_id = $request->nota_id;
        $notaE_detalles->fecha = (empty($request->fecha))? date('Y-m-d h:i:s') : $request->fecha;
        $notaE_detalles->concepto = $request->concepto;
        $notaE_detalles->detalles = $request->detalles;
        $resultado = $notaE_detalles->save();

        return response()->json(array('result' => $resultado));
    }

    private function notaElectronica($id){

        $detalles = array();

        $nota = FacturaNota::findOrFail($id);

        $facturacion_api = $nota->factura->proyecto->facturacion_api;
        $tipo_nota = 0;
        $total_impuestos = 0;

        if ($nota->tipo_nota == 'DEBITO') {
            $tipo_nota = 2;
        }elseif ($nota->tipo_nota == 'CREDITO') {
            $tipo_nota = 3;
        }

        if (!empty($nota->factura->cliente->municipio)) {
            $ciudad = $nota->factura->cliente->municipio->CodigoDaneMunicipio;
            $departamento = $nota->factura->cliente->municipio->departamento->CodigoDaneDepartamento;            
        }else{
            $ciudad = $nota->factura->cliente->ubicacion->municipio->CodigoDaneMunicipio;
            $departamento = $nota->factura->cliente->ubicacion->municipio->departamento->CodigoDaneDepartamento;            
        }

        if (strlen($ciudad) < 3){
            for ($i=strlen($ciudad); $i <= 3; $i++) { 
                $ciudad = '0' . $ciudad;
                $i+=1;
            }
        }

        $telefono = '';
        $direccion = '';

        $telefono = "";

        if (!empty($nota->factura->cliente->TelefonoDeContactoMovil) && $nota->factura->cliente->TelefonoDeContactoMovil != "") {
           $telefono = $nota->factura->cliente->TelefonoDeContactoMovil;

        }elseif (!empty($nota->factura->cliente->TelefonoDeContactoFijo)) {
            $telefono = $nota->factura->cliente->TelefonoDeContactoFijo;
        }

        if (!empty($nota->factura->cliente->DireccionDeCorrespondencia) && $nota->factura->cliente->DireccionDeCorrespondencia != "") {
           $direccion = $nota->factura->cliente->DireccionDeCorrespondencia;

        }elseif (!empty($nota->factura->cliente->DireccionNomenclatura)) {
            $direccion = $nota->factura->cliente->DireccionNomenclatura;
        }

        $data = new Data;

        foreach ($nota->producto as $producto) {
            $detalles[] = $data->facturaAddDetalles
            (
                $producto->concepto, #Concepto
                $producto->cantidad, #Cantidad
                $producto->valor_unidad, #Valor Unitario
                $producto->valor_iva, #Total Impuestos                
                $producto->valor_total, #Total
                $producto->iva, #porcentaje de IVA
                $producto->valor_iva #Total Iva,
            );

            $total_impuestos += $producto->valor_iva;
        }

        $nota_reporte = $data->factura_electronica(
            $tipo_nota,//tipo de documento
            $nota->tipo_concepto->codigo,//tipo_concepto.
            $nota->tipo_operacion->codigo,//tipo de operacion
            $nota->tipo_negociacion->codigo,//tipo de negociacion
            $nota->tipo_medio_pago->codigo,//tipo de medio de pago
            $nota->factura->factura_electronica->numero_factura_dian,//documento_relacionado
            $nota->factura->FechaDePago,//fecha_vencimiento
            $nota->valor_total,//valor_total
            $total_impuestos,//total_impuestos
            '',//informacion Adicional
            $departamento,//departamento
            $ciudad,//ciudad
            $nota->factura->cliente->Identificacion,//cedula
            $nota->factura->cliente->NombreBeneficiario,//nombres
            $nota->factura->cliente->Apellidos,//apellidos
            $nota->factura->cliente->CorreoElectronico,//correo
            $telefono,//telefono
            $direccion,//direccion
            $detalles,//detalles
            null,//Campos adicionales
            intval($nota->descuento),//porcentaje_descuento
            $nota->motivo_descuento,//motivo_descuento,
            $nota->fecha_expedision
        );      

        return array('datosjson' => $nota_reporte, 'api' => $facturacion_api);
    }

    public function reportar(Request $request){
        

        if (Auth::user()->can('facturacion-notas-reportar')) {

            $data = $this->notaElectronica($request->nota_id);

            return response()->json(['nota_electronica' => $data['datosjson'], 'api' => $data['api'], 'respuesta' => 'reportar', 'reportar' => true, 'nota_id' => $request->nota_id]);
        }else{
            abort(403);
        }
    }

    public function exportar(Request $request){

        if (Auth::user()->can('facturacion-notas-exportar')) {
            Excel::create('notas', function($excel) use($request) {

                $datos = array();
                $datos1 = array();
     
                $excel->sheet('Notas', function($sheet) use($request) {

                    

                    $notas = FacturaNota::select(
                        'facturas_notas.id',
                        'Clientes.Identificacion',
                        'Clientes.NombreBeneficiario',
                        'Clientes.Apellidos',
                        'Municipios.NombreMunicipio',
                        'Municipios.NombreDepartamento',
                        'NumeroDeProyecto', 
                        'tipo_nota',
                        'facturas_notas.valor_total',
                        'facturas_notas.descuento',
                        'facturas_notas.motivo_descuento',
                        'facturas_notas.numero_nota_dian',
                        'facturas_notas.factura_id',
                        'fe.numero_factura_dian', 
                        'fa.Periodo',
                        'facturas_notas.archivo',
                        'facturas_notas.created_at')
                    ->join('Facturacion as fa', 'facturas_notas.factura_id', '=', 'fa.FacturaId')               
                    ->join('Clientes', 'Clientes.ClienteId', '=', 'fa.ClienteId')
                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')                    
                    ->join('Proyectos', 'Proyectos.ProyectoID', '=', 'Clientes.ProyectoId')                   
                    
                    ->leftJoin('facturas_electronicas as fe', 'fa.FacturaId', '=', 'fe.FacturaId')
                    ->Cedula($request->documento)
                    ->Proyecto($request->proyecto)
                    ->Tipo($request->tipo_nota)
                    ->Periodo($request->periodo)
                    ->get();

                    foreach ($notas as $key) {                    

                        $datos[] = array(
                            'NOTA ID' => $key->id,
                            'CEDULA' => $key->Identificacion,
                            'NOMBRE' => mb_convert_case($key->NombreBeneficiario.' '. $key->Apellidos, MB_CASE_TITLE, "UTF-8"),
                            'MUNICIPIO' => $key->NombreMunicipio,
                            'DEPARTAMENTO' => $key->NombreDepartamento,
                            'PROYECTO' => $key->NumeroDeProyecto,
                            'TIPO NOTA' => $key->tipo_nota,
                            'PERIODO' => $key->Periodo,
                            'VALOR NOTA' => intval(($key->tipo_nota == 'CREDITO')? $key->valor_total * -1 : $key->valor_total),
                            'DESCUENTO' => $key->descuento,
                            'MOTIVO DESCUENTO' => $key->motivo_descuento,
                            'TOTAL DESCUENTO' => (($key->descuento/100) * $key->valor_total),
                            'NUMERO NOTA DIAN' => $key->numero_nota_dian,
                            'FACTURA_ID' => $key->factura_id,
                            'FACTURA DIAN' => $key->numero_factura_dian,
                            'ARCHIVO NOTA' => $key->archivo,
                            'FECHA DE CREACION' => $key->created_at
                        );
                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('notas.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    $sheet->fromArray($datos, true, 'A1', true);
     
                });

                $excel->sheet('Items Notas', function($sheet) use($request){

                    $items = FacturaNota::select('np.factura_nota_id','np.concepto','np.cantidad','np.valor_unidad','np.iva','np.valor_iva','np.valor_total')
                        ->join('notas_productos as np', 'facturas_notas.id', '=', 'np.factura_nota_id')
                        ->Cedula($request->documento)
                        ->Tipo($request->tipo_nota)
                        ->Periodo($request->periodo)
                        ->get();

                    foreach ($items as $item) {
                        $datos1[] = array(
                            'NOTA ID' => $item->factura_nota_id,
                            'CONCEPTO' => $item->concepto,
                            'CANTIDAD' => floatval($item->cantidad),
                            'VALOR/UNIDAD' => floatval($item->valor_unidad),
                            'IVA' => (int) $item->iva,
                            'VALOR IVA' => floatval($item->valor_iva),
                            'TOTAL' => floatval($item->valor_total)
                        );
                    }

                    $sheet->fromArray($datos1, true, 'A1', true);

                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }

}
