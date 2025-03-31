<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facturacion;
use App\Novedad;
use App\Proyecto;
use App\Cliente;
use App\ClienteContrato;
use App\ContratoServicio;
use App\FacturaNovedad;
use App\Departamento;
use Excel;
use DB;

class NovedadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('novedades-listar')) {

            $novedades = Novedad::
            Cedula($request->get('documento'))
            ->Concepto($request->get('concepto'))
            ->Fechas($request->get('fecha_inicio'), $request->get('fecha_fin'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->Estado($request->get('estado'))
            ->orderBy('fecha_inicio', 'DESC')
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $conceptos = Novedad::select('concepto')->groupBy('concepto')->orderBy('concepto', 'ASC')->get();
            $estados = [
                "FINALIZADA",
                "PENDIENTE",
                "SALDADO",
                "SIN FINALIZAR"
            ];


            return view('adminlte::facturacion.novedades.index',compact('novedades', 'proyectos', 'departamentos', 'conceptos', 'estados'));
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
        if (Auth::user()->can('novedades-crear')) {
            $conceptos = [
                [
                    'nombre' => 'Ajustes por falta de servicio',
                    'cobrar' => false
                ],
                [
                    'nombre' => 'Cargador ONT',
                    'cobrar' => true,
                ],
                [
                    'nombre' => 'Equipo ONT',
                    'cobrar' => true,
                ],
                [
                    'nombre' => 'Reconexion',
                    'cobrar' => true,
                ],
                [
                    'nombre' => 'Suspensión por Mora',
                    'cobrar' => false,
                ],
                [
                    'nombre' => 'Suspensión Temporal',
                    'cobrar' => false,
                ],
                [
                    'nombre' => 'Traslado',
                    'cobrar' => true,
                ],
                [
                    'nombre' => 'Metro Fibra Adicional',
                    'cobrar' => true,
                ],
                [
                    'nombre' => 'Servicio de Internet',
                    'cobrar' => true,
                ]
            ];

            $unidades_medidas = ['MINUTOS', 'HORAS', 'DIAS', 'MES', 'UNIDAD', 'METROS'];

            return view('adminlte::facturacion.novedades.create', compact('conceptos', 'unidades_medidas'));
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

        if (Auth::user()->can('novedades-crear')) {
            
            $this->validate(request(),[
                'cliente_id' => 'required', 
                'conceptos' => 'required']
            );            

            $result = DB::transaction(function () use($request) {
               
                foreach ($request->conceptos as $concepto) {
                    $novedad_existe = Novedad::where([
                        ['concepto', $concepto['concepto']], 
                        ['fecha_inicio', str_replace('T',' ',$concepto['fecha_inicio'])], 
                        ['fecha_fin', str_replace('T',' ',$concepto['fecha_fin'])],
                        ['ClienteId', $request->cliente_id]
                    ])->count();

                    if ($novedad_existe > 0) {
                        DB::rollBack();
                        return ['respuesta' => 'La novedad ' . $concepto['concepto'] . ' ya existe!', 'tipo_mensaje' => 'error'];
                    }else{
                        $facturacion = Facturacion::select('FacturaId')
                        ->where(function ($query) use($concepto) {
                            if (!empty($concepto['fecha_fin'])) {
                                $query->where('Periodo', str_replace('-', '',date("Y-m", strtotime($concepto['fecha_fin']."+ 1 month"))));
                            }else{
                                $query->where('Periodo', str_replace('-', '',date("Y-m", strtotime($concepto['fecha_inicio']."+ 1 month"))));
                            }
                        })
                        ->whereIn('ClienteId', [$request->cliente_id])
                        ->count();

                       
                        if ($facturacion > 0) {
                        //if(false){
                            DB::rollBack();
                            return ['respuesta' => 'Ya se generó factura del periodo de la novedad "' . $concepto['concepto'] .'"', 'tipo_mensaje' => 'error'];
                        }else{

                            $novedad = new Novedad;
                            $novedad->concepto = $concepto['concepto'];
                            $novedad->cantidad = $concepto['cantidad'];
                            $novedad->valor_unidad = $concepto['valor_unidad'];
                            $novedad->unidad_medida = $concepto['unidad_medida'];
                            $novedad->iva = $concepto['iva'];
                            $novedad->fecha_inicio = str_replace('T',' ',$concepto['fecha_inicio']);
                            $novedad->fecha_fin = str_replace('T',' ',$concepto['fecha_fin']);
                            $novedad->estado = 'PENDIENTE';
                            $novedad->ClienteId = $request->cliente_id;
                            $novedad->cobrar = $concepto['cobrar'];
                            $novedad->user_id = Auth::user()->id;

                            if (!$novedad->save()) {
                                DB::rollBack();
                                return ['respuesta' => 'Error al crear la Novedad "' . $concepto['concepto'] .'"', 'tipo_mensaje' => 'error'];
                            }
                        }
                    }
                }

                return ['respuesta' => 'novedad creada satisfactoriamente!','tipo_mensaje' => 'success'];                    
                
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
        if (Auth::user()->can('novedades-ver')) {

            $data = array();

            $novedad = Novedad::findOrFail($id);

            $mantenimiento = array();
            $parada_reloj = array();

            if (count($novedad->ticket) > 0) {
                if (count($novedad->ticket->mantenimiento) > 0) {
                    $mantenimiento = $novedad->ticket->mantenimiento;                    

                    if (count($mantenimiento->parada_reloj) > 0) {
                        $parada_reloj = $mantenimiento->parada_reloj;
                    }

                }
            }
            




            $data['id'] = $novedad->id;
            $data['nombre'] = mb_convert_case($novedad->cliente->NombreBeneficiario . ' ' . $novedad->cliente->Apellidos, MB_CASE_TITLE, "UTF-8");
            $data['municipio'] = $novedad->cliente->municipio->NombreMunicipio. ' - ' .$novedad->cliente->municipio->NombreDepartamento;
            $data['proyecto'] = $novedad->cliente->proyecto->NumeroDeProyecto;
            $data['fecha_inicio'] = $novedad->fecha_inicio;
            $data['fecha_fin'] = $novedad->fecha_fin;
            $data['ticket'] = $novedad->ticket_id;
            $data['concepto'] = $novedad->concepto;
            $data['cantidad'] = $novedad->cantidad;
            $data['unidad_medida'] = $novedad->unidad_medida;
            $data['valor'] = $novedad->valor_unidad;
            $data['iva'] = $novedad->iva;
            $data['estado'] = $novedad->estado;
            $data['mantenimiento'] = $mantenimiento;
            $data['parada_reloj'] = $parada_reloj;


            return response()->json($data);
            
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
        if (Auth::user()->can('novedades-actualizar')) {

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
        if (Auth::user()->can('novedades-actualizar')) {

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
        if (Auth::user()->can('novedades-eliminar')) {
            $novedad = Novedad::findOrFail($id);

            if (count($novedad->factura_novedad) == 0 && count($novedad->ticket) == 0) {
                if ($novedad->delete()) {
                    return redirect()->route('novedades.index')->with('success', 'Se eliminó correctamente.');
                }else{
                    return redirect()->route('novedades.index')->with('error', 'Error al eliminar la novedad!');
                }
            }else{
                return redirect()->route('novedades.index')->with('error', 'La novedad tiene relacionada facturas o tickets.'); 
            }


        }else{
            abort(403);
        }
    }

    public function redondear($valor){
        $pesos = substr($valor, -2);
        if ($pesos > 50){
            $valor = $valor + (100 - $pesos);
        }elseif($pesos < 50){
            $valor = $valor - $pesos;
        }

        return $valor;
    }

    public function cerrar(Request $request){

        if (Auth::user()->can('novedades-cerrar')) {

            $this->validate(request(),[
                'novedad_id' => 'required', 
                'fecha_fin' => 'required']
            );

            $novedad = Novedad::find($request->novedad_id);

            if (!empty($request->ticket) && !empty($novedad->ticket_id)) {
                if ($novedad->ticket_id != $request->ticket) {
                    return redirect()->route('novedades.index')->with('error', 'La novedad le corresponde a un ticket diferente.');
                }
            }

            

            

            $date1 = new \DateTime($novedad->fecha_inicio);
            $date2 = new \DateTime($request->fecha_fin);
            $diferencia = $date1->diff($date2);
            $dias_sin_servicio = ((($diferencia->m) + ($diferencia->y * 12)) * 30) + $diferencia->d;
            $minutos_sin_servicio = ($diferencia->days * 1440) + ($diferencia->h * 60) + $diferencia->i;

            //if ($dias_sin_servicio < 3) {
            if ($minutos_sin_servicio < 420) {

                if ($novedad->delete()) {
                    return redirect()->route('novedades.index')->with('warning', 'Novedad no superó los 420 minutos sin servicio. se elimina del sistema.');
                }else{
                    return redirect()->route('novedades.index')->with('error', 'Error al eliminar la novedad');
                }
            }

            //dd($minutos_sin_servicio);

            
            $novedad->ticket_id = $request->ticket;
            $novedad->fecha_fin = str_replace('T',' ',$request->fecha_fin);


            if ($novedad->save()) {
                return redirect()->route('novedades.index')->with('success', 'Novedad cerrada correctamente.');
            }else{
                return redirect()->route('novedades.index')->with('error', 'Error al cerrar la novedad');
            }
            

        }else{
            abort(403);
        }

    }


    public function exportar(Request $request){

        if (Auth::user()->can('novedades-exportar')) {
            Excel::create('novedades', function($excel) use($request) {
     
                $excel->sheet('Novedades', function($sheet) use($request) {

                    $datos = array();

                    $novedades = Novedad::
                    Concepto($request->get('concepto'))
                    ->Fechas($request->get('fecha_inicio'), $request->get('fecha_fin'))                    
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado($request->get('estado'))
                    ->get();

                    foreach ($novedades as $key) {
                        $datos[] = array(
                            'NOVEDAD ID' => $key->id,
                            'CEDULA' => $key->cliente->Identificacion,
                            'NOMBRE' => mb_convert_case($key->cliente->NombreBeneficiario.' '. $key->cliente->Apellidos, MB_CASE_TITLE, "UTF-8"),
                            'MUNICIPIO' => $key->cliente->municipio->NombreMunicipio,
                            'DEPARTAMENTO' => $key->cliente->municipio->NombreDepartamento,
                            
                            'PROYECTO' => $key->cliente->proyecto->NumeroDeProyecto,
                            'CONCEPTO' => $key->concepto,
                            'CANTIDAD' => $key->cantidad,
                            'UNIDAD DE MEDIDA' => $key->unidad_medida,
                            'IVA' => intval($key->iva),
                            'VALOR UNIDAD' => $key->valor_unidad,
                            'FECHA INICIO' => $key->fecha_inicio,
                            'FECHA FIN' => $key->fecha_fin,
                            'ESTADO' => $key->estado,
                            'TICKET' => $key->ticket_id,
                            'ESTADO TICKET' => (empty($key->ticket)) ? '': $key->ticket->estado->Descripcion,
                            'AGENTE CREO' => $key->user->name
                        );
                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('novedades.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }

    public function masivas(){
        if (Auth::user()->can('novedades-masivas')) {
            return view('adminlte::facturacion.novedades.masivas');
        }else{
            abort(403);
        }
        
    }

    public function agregar_masivas(Request $request){
        if (Auth::user()->can('mantenimientos-novedades-crear')) {
            $this->validate(request(),[
                'concepto' => 'required', 
                'cantidad' => 'required', 
                'unidad_medida' => 'required', 
                'iva' => 'required', 
                'cedulas' => 'required',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'valor_unidad' => 'required',
                'forma_aplicacion' => 'required'
            ]);

            switch ($request->concepto) {
                case 'Compensación por indisponibilidad':
                    $concepto = $request->concepto . ' ' . strtoupper(strftime("%B %Y",strtotime($request->fecha_inicio)));
                    break;
                case 'otro':
                    $concepto = $request->otro;
                    break;
                
                default:
                    $concepto = $request->concepto;
                    break;
            }
            

            $cedulas = explode(',', $request->cedulas);

            foreach ($cedulas as $key => $value) {                
                
                $cliente = Cliente::select('ClienteId')
                //->where([['Identificacion',$value],['Estrato', '2']])
                ->where('Identificacion',$value)
                ->whereNotIn('Status', ['INACTIVO'])
                ->first();

                if (count($cliente) > 0) {

                    $novedad_existe = Novedad::where([
                        ['concepto', $concepto],                
                        ['ClienteId', $cliente->ClienteId],
                        ['fecha_inicio', str_replace("T"," ",$request->fecha_inicio).":00"],
                        ['mantenimiento_id', $request->mantenimiento]
                    ])->count();

                    if ($novedad_existe > 0) {                    
                        continue;
                    }else{

                        $contrato = ClienteContrato::select('id')
                        ->where([
                            ['ClienteId', $cliente->ClienteId], 
                            ['estado', 'VIGENTE']
                        ])
                        ->orderBy('id','DESC')
                        ->first();

                        if (count($contrato) > 0) {

                            $servicio = ContratoServicio::where('contrato_id', $contrato->id)
                            ->orderBy('id','DESC')
                            ->first();

                            if (count($servicio) > 0) {

                                $valor_unidad = $request->valor_unidad;

                                if ($valor_unidad == 0) {                                

                                    switch ($request->unidad_medida) {
                                        case 'MINUTOS':
                                            $valor_unidad = (((intval($servicio->valor) / 30) / 24) / 60);
                                            break;
                                        case 'HORAS':
                                            $valor_unidad = ((intval($servicio->valor) / 30) / 24);
                                            break;
                                        case 'DIAS':
                                            $valor_unidad = (intval($servicio->valor) / 30);
                                            break;
                                        case 'MES':
                                            $valor_unidad = intval($servicio->valor);
                                            break;
                                    }
                                }

                                //$fecha_inicio = date('Y-m-d',strtotime($request->mes.'-01'));
                                //$fecha_fin = date('Y-m-d',strtotime($request->mes.'-30'));

                                $novedad = new Novedad;
                                $novedad->concepto = $concepto;
                                $novedad->cantidad = $request->cantidad;

                                if($request->forma_aplicacion == "DESCONTAR"){

                                    $novedad->valor_unidad = round($valor_unidad, 2) * (-1);

                                }else if ($request->forma_aplicacion == "COBRAR"){

                                    $novedad->valor_unidad = round($valor_unidad, 2);
                                    
                                }

                                
                                $novedad->unidad_medida = $request->unidad_medida;
                                $novedad->iva = $request->iva;
                                $novedad->fecha_inicio = str_replace("T"," ",$request->fecha_inicio).":00";
                                $novedad->fecha_fin = str_replace("T"," ",$request->fecha_fin).":00";
                                $novedad->estado = 'PENDIENTE';
                                $novedad->ClienteId = $cliente->ClienteId;
                                $novedad->cobrar = true;
                                $novedad->user_id = Auth::user()->id;
                                $novedad->ticket_id = $request->ticket;
                                $novedad->mantenimiento_id = $request->mantenimiento;

                                if (!$novedad->save()) {
                                    return redirect()->route('novedades.masivas')->with('error', 'Error al guardar la novedad para la cedula ' . $value);
                                }
                            }else{
                                continue;
                            }
                        }else{
                            continue;
                        }
                    }
                }else{
                    continue;
                }
            }

            return redirect()->route('novedades.index')->with('success', 'La novedad se agregó masivamente!');


        }else{
            abort(403);
        }
    }

    public function ver($id)
    {    
        if (Auth::user()->can('novedades-ver')) {

            $novedad = Novedad::with('user')->where('id',$id)->findOrFail($id);



            $facturas_novedades = FacturaNovedad::with('factura')->where('novedad_id',$id)->get();

            $data = [];

            foreach ($facturas_novedades as $key) {
                $item = array();
                $item['factura_id'] = $key->factura_id;
                $item['periodo'] = strtoupper(strftime("%B %Y",strtotime(substr($key->factura->Periodo,0,4) .'-'. substr($key->factura->Periodo,4))));
                $item['valor_total'] = number_format($key->factura->ValorTotal,2, ',', '.');
                $data[] = $item;
            }


          //return response()->json(array('novedad' => $novedad , 'facturas_novedades' => $facturas_novedades));
            return response()->json(['novedad' => $novedad , 'facturas_novedades' => $data]);




        }else{
            abort(403);
        }
    }
}
