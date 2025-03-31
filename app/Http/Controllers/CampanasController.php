<?php

namespace App\Http\Controllers;

use App\CampanaCamposOpciones;
use Illuminate\Http\Request;
use App\Cliente;
use App\Campana;
use App\CampanaCampos;
use App\CampanaClientes;
use App\Departamento;
use App\CampanaRespuestas;
use App\CamposVisualizar;
use App\Facturacion;
use App\Solicitud;
use App\AtencionCliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Excel;
use Charts; 

class CampanasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('campañas-listar')) {

            $campañas = Campana::orderByRaw("CASE 
                                WHEN estado = 'EN EJECUCION' THEN 1
                                WHEN estado = 'POR EJECUTAR' THEN 2
                                ELSE 3
                            END")
                            ->Nombre($request->get('nombre'))
                            ->Tipo($request->get('tipo'))
                            ->Mes($request->get('mes'))
                            ->Estado($request->get('estado'))
                            ->latest()
                            ->paginate(15);   

            $tipo_campañas = ['FACTURACION','CLIENTES'];

            $estados = ['POR EJECUTAR','EN EJECUCION','FINALIZADA'];

            return view('adminlte::campana.index',compact(
                'campañas','tipo_campañas','estados'
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
    public function create(Request $request)
    {
        if (Auth::user()->can('campañas-crear')) { 
            // 
            $fecha_actual = date('Y-m');
            $mes_anterior = str_replace("-","",date('Y-m', strtotime('-1 month', strtotime($fecha_actual))));
            $proyectos = Facturacion::select('Proyectos.ProyectoId', 'Proyectos.NumeroDeProyecto')
            ->join('Proyectos', 'Facturacion.ProyectoId', '=', 'Proyectos.ProyectoID')
            ->where('Facturacion.Periodo',$mes_anterior)
            ->distinct()
            ->get();            
            
            $estados_cliente = ['ACTIVO','INACTIVO','EN INSTALACION','PENDIENTE','RECHAZADO'];
            
            $tipo_campañas = ['FACTURACION','CLIENTES'];

            $tipos_campos = [
                'ARCHIVO',
                'FECHA',
                'NUMERICO',
                'SELECCION_CON_MULTIPLE_RESPUESTA',
                'SELECCION_CON_UNICA_RESPUESTA',
                'TEXT',
                'TEXTAREA'
            ];

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();


            $campos_cliente = $this->getCamposCliente();

            $campos_facturacion = $this->getCamposFacturacion();

            $ultimos = Facturacion::select('periodo')
                            ->distinct()
                            ->orderBy('periodo','desc')
                            ->take(5)
                            ->get();

            $ultimos_periodos  = [];

            foreach ($ultimos as $periodo){
                $ultimos_periodos [] = $periodo->periodo;
            }
            
            return view('adminlte::campana.create',compact(
                'proyectos',
                'estados_cliente',
                'tipo_campañas',
                'campos_cliente',
                'campos_facturacion',
                'ultimos_periodos',
                'tipos_campos',
                'departamentos'
            ));

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
        if (Auth::user()->can('campañas-crear')) {

            $this->validate(request(),[
                'nombre_campana' => 'required',
                'fecha_inicio' => 'required',
                'tipo_campana' => 'required' ,
                'campos' => 'required', 
                'nombres' => 'required',
                'tipo'  => 'required'      
            ]);
                 
            DB::transaction(function() use($request){
                
                #creacion de la campaña--------
                $campana = new Campana(); 
                $nombre_campana = ucfirst($request->nombre_campana);
                $campana->nombre = $nombre_campana;
                $campana->tipo = $request->tipo_campana;
                $campana->fecha_inicio = $request->fecha_inicio;
                $fecha_actual = strtotime(date("Y-m-d"));
                $fecha_entrada = strtotime($request->fecha_inicio);
                $campana->sin_restricciones = ($request->restricciones == 1)? true : false;

                if($fecha_actual == $fecha_entrada){
                    $campana->estado = 'EN EJECUCION';
                }elseif($fecha_actual < $fecha_entrada){
                    $campana->estado = 'POR EJECUTAR';
                }else{
                    return redirect()->route('campanas.index')->with('error', 'Error. Fecha no admitida');
                }

                if($request->tipo_campana == 'FACTURACION'){
                    $periodo = str_replace("-","",$request->periodo);
                    $campana->periodo_facturacion = $periodo;
                }

                if(!empty($request->cuotas_max_acuerdo)){
                    $campana->cuotas_max_acuerdo = $request->cuotas_max_acuerdo;
                }else{
                    $campana->cuotas_max_acuerdo = 0; 
                }

                if(!empty($request->valor_perdonar)){
                    $campana->valor_pardonar_acuerdo = $request->valor_perdonar;
                }else{
                    $campana->valor_pardonar_acuerdo = 0;
                }

                if(!empty($request->perdonar_porcentual)){
                    $campana->tipo_descuento = $request->perdonar_porcentual;
                }

                if($campana->save()){
                    
                    #creamos los campos para visualizar en tabla--                                
                    foreach($request->campos as $campo){
                        $camposvisualizar = new CamposVisualizar();
                        $camposvisualizar->campo = $campo;
                        $camposvisualizar->campana_id = $campana->id;

                        if(!$camposvisualizar->save()){
                            DB::rollBack();
                            return redirect()->route('campanas.create')->with('error', 'Error. Al asignar los campos a visualizar.');
                        }
                    }                
                    
                    #creamos campos para las respuestas de la campaña

                    #crear dos campos internos para dato especifico de la solicitud
                    $motivo = $this->crearCampanaCampo('Motivo_atencion', 'text', $campana->id , '');
                    $categoria = $this->crearCampanaCampo('Categoria_atencion', 'text', $campana->id,'');

                    if($motivo != true or $categoria != true){
                        DB::rollBack();
                        return redirect()->route('campanas.create')->with('error', 'Error. Al crear los Campos de la campaña.');
                    }

                    foreach($request->nombres as $indice => $nombre_campo){                                    
                        #creacion normal de los campos
                        $respuesta = $this->crearCampanaCampo($nombre_campo, $request->tipo[$indice], $campana->id , $request);
                        
                        if(!$respuesta){
                            DB::rollBack();
                            return redirect()->route('campanas.create')->with('error', 'Error. Al crear los Campos de la campaña.' . $nombre_campo);
                        }                                                       
                    }

                    $cedulas_especificas = array();
                    if(!empty($request->cedulas_especificas)){
                        //convertimos las cedulas para los clientes especificos                 
                        if(!empty($request->cedulas_especificas)){
                            $cedulas_especificas = explode(',', $request->cedulas_especificas);
                            
                        }
                    }
                
                    if($request->tipo_campana == 'CLIENTES'){
                        
                        $clientes = Cliente::select('Clientes.ClienteId','Clientes.Identificacion')
                                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                                    ->leftJoin('clientes_restricciones', 'Clientes.ClienteId', '=', 'clientes_restricciones.cliente_id')                                    
                                    ->where(function($query) use($request, $cedulas_especificas ){

                                        if(!empty($request->municipio)){
                                            $query->where('municipio_id',$request->municipio);
                                        }

                                        if(!empty($request->departamento)){
                                            $query->where('Municipios.DeptId',$request->departamento);
                                        }

                                        if(!empty($request->proyecto)){
                                            $query->where('ProyectoId',$request->proyecto);
                                        }

                                        if(!empty($cedulas_especificas)){
                                            $query->whereIn('Clientes.Identificacion', $cedulas_especificas);
                                        }

                                        if($request->restricciones != 1){
                                            $query->whereNull('clientes_restricciones.cliente_id');
                                        }
                                    })
                                    ->where('Clientes.Status', $request->estado_cliente)
                                    ->get();
                                     
                        if(count($clientes) > 0){
                        
                            foreach($clientes as $cliente){

                                $campana_cliente = new CampanaClientes();
                                $campana_cliente->estado = 'PENDIENTE';
                                $campana_cliente->campana_id = $campana->id;
                                $campana_cliente->cliente_id = $cliente->ClienteId;

                                if(!$campana_cliente->save()){
                                    DB::rollBack();
                                    return redirect()->route('campanas.create')->with('error', 'Error. Al registrar los clientes .');
                                }                           
                                
                            }
                        }else{                       
                            DB::rollBack();
                            return redirect()->route('campanas.create')->with('error', 'Error. No hay clientes con esas indicaciones.');
                        }

                    }else{                                       
                    
                        $facturas = Facturacion::select('Facturacion.ClienteId','Facturacion.Internet','Facturacion.ValorTotal','Facturacion.Identificacion')
                        ->join('Clientes', 'Facturacion.ClienteId', '=', 'Clientes.ClienteId')
                        ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                        ->leftJoin('clientes_restricciones', 'Clientes.ClienteId', '=', 'clientes_restricciones.cliente_id')                        
                        ->where(function($query) use($request , $cedulas_especificas){


                            if(!empty($request->municipio)){
                                $query->where('Clientes.municipio_id',$request->municipio);
                            }

                            if(!empty($request->departamento)){
                                $query->where('Municipios.DeptId',$request->departamento);
                            }

                            if(!empty($request->proyecto)){
                                $query->where('Clientes.ProyectoId',$request->proyecto);

                            }

                            if(!empty($cedulas_especificas)){
                                $query->whereIn('Clientes.Identificacion',$cedulas_especificas);
                            }

                            if($request->restricciones != 1){
                                $query->whereNull('clientes_restricciones.cliente_id');
                            }
                                                                    
                        })->where('Facturacion.Periodo', $periodo)
                        ->get();

                        if(count($facturas) > 0){

                            foreach($facturas as $factura){

                                $campana_cliente = new CampanaClientes();
                                $campana_cliente->estado = 'PENDIENTE';
                                $campana_cliente->campana_id = $campana->id;
                                $campana_cliente->cliente_id = $factura->ClienteId;
        
                                if(!$campana_cliente->save()){
                                    DB::rollBack();
                                    return redirect()->route('campanas.create')->with('error', 'Error. Al registrar las facturas.');
                                }                               
                                                        
                            }

                        }else{                        
                            DB::rollBack();
                            return redirect()->route('campanas.create')->with('error', 'Error. No hay facturas con esas indicaciones con esas indicaciones.');
                        }
                                                                                                                                        
                    }
                
                }else{
                    DB::rollBack();
                    return redirect()->route('campanas.index')->with('error', 'Error. Al crear la Campaña.');
                }
                return redirect()->route('campanas.index')->with('success', 'Campaña creada.');
            });
            return redirect()->route('campanas.index');  

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
    public function show(Request $request , $id) 
    {
        if (Auth::user()->can('campañas-ver')) {

            $campaña = Campana::find($id);

            $campo_estado = CampanaCampos::where([
                ['campana_id', $id], 
                ['nombre', 'estado']
            ])->first();

            $estados = null;

            if(!empty($campo_estado)){
                $estados = $campo_estado->opciones->pluck('valor')->toArray();

            }else{
                $estados = [
                    'PENDIENTE',
                    'CONTESTA',
                    'NO CONTESTA',
                    'EQUIVOCADO',
                    'VOLVER A LLAMAR', 
                    'NO LLAMAR',                            
                ];
            }

            sort($estados);

            if($campaña->estado == 'EN EJECUCION' or auth()->user()->can('campañas-ejecucion')){

                $departamentos = CampanaClientes::selectRaw('DISTINCT m.DeptId as id, m.NombreDepartamento as nombre')
                ->join('Clientes as c', 'campanas_clientes.cliente_id', '=', 'c.ClienteId')
                ->join('Municipios as m', 'c.municipio_id', '=', 'm.MunicipioId')
                ->where('campanas_clientes.campana_id', $id)                
                ->get();

                $clientes = null ;
                $graficar = null;
            
                $clientes = CampanaClientes::select('campanas_clientes.*')                
                ->Cedula($request->get('documento'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->Estado($request->get('estado'))
                ->Barrio($request->get('barrio'))
                ->Mora($request->get('mora_desde'), $request->get('mora_hasta'))
                ->leftJoin('clientes_restricciones', 'campanas_clientes.cliente_id', '=', 'clientes_restricciones.cliente_id')
                ->where('campanas_clientes.campana_id', $id)
                ->where(function($query)  use($request){
                    $query->where('fecha_hora_rellamar', '<', date('Y-m-d H:i:s'))->orWhereNull('fecha_hora_rellamar');
                })
                ->where(function($query)  use($request, $campaña){
                    if($request->solicitudes_pendientes == '1'){
                        $query->whereHas('solicitud', function ($query){
                            $query->where('estado','PENDIENTE');                     
                        });
                    }else{

                        if(!$campaña->sin_restricciones){
                            $query->whereNull('clientes_restricciones.cliente_id');

                            $query->whereDoesntHave('solicitud', function ($query){
                                $query->where('estado','PENDIENTE');
                                //->whereNotIn('campanas_clientes.estado', ['CONTESTA','EQUIVOCADO','NO LLAMAR']);                       
                            })->whereNotExists(function ($query) {
                                $query->from('solicitudes')
                                ->join('atencion_clientes', 'solicitudes.atencion_cliente_id', '=', 'atencion_clientes.id')
                                ->whereRaw('atencion_clientes.cliente_id = campanas_clientes.cliente_id')
                                ->where('solicitudes.estado', 'PENDIENTE');
                                //->whereNotIn('campanas_clientes.estado', ['CONTESTA','EQUIVOCADO','NO LLAMAR']);
                            });

                            $query->whereNotExists(function ($query) {
                                $query->from('Clientes')
                                ->join('ClientesTickets as ct', 'Clientes.ClienteId', '=', 'ct.ClienteId')
                                ->whereRaw('ct.ClienteId = campanas_clientes.cliente_id')
                                ->where('ct.EstadoDeTicket', 6);
                            });
                        }
                    }
                })                                          
                ->orderByRaw("CASE WHEN campanas_clientes.estado = 'PENDIENTE' THEN 0 ELSE 1 END");

                foreach ($clientes->get() as $dato) {

                    if (!empty($dato->cliente->Latitud) && is_numeric($dato->cliente->Latitud) && $dato->estado == 'PENDIENTE') {        
                        $graficar[] = array(
                            'id' => $dato->id,
                            'nombre' => $dato->cliente->NombreBeneficiario . ' ' . $dato->cliente->Apellidos,
                            'latitud' => $dato->cliente->Latitud, 
                            'longitud' => $dato->cliente->Longitud,
                            'direccion' =>  $dato->cliente->DireccionDeCorrespondencia,
                            'barrio' => $dato->cliente->Barrio
                        );
                    }        
                }
        
                $graficar = json_encode($graficar);

                $clientes = $clientes->paginate(15);

                return view('adminlte::campana.show',compact(
                    'campaña',
                    'clientes',
                    'estados', 
                    'departamentos',
                    'graficar'
                ));

            }else{
                abort(403);
            }

        }else{
            abort(403);
        }
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
        if (Auth::user()->can('campañas-editar')) {
        //        
            $campana = Campana::find($id); 

            if($campana->estado != 'FINALIZADA'){       

                $campo = [];
                $posicion = 0;
                if($campana->tipo == 'FACTURACION'){
                    $campos_ver = [
                        'FACTURAID',
                        'TELEFONOS DE CONTACTO CLIENTE',
                        'PERIODO FACTURA',
                        'PROYECTO',
                        'SALDO A FAVOR',
                        'VALOR TARIFA',
                        'PLAN CONTRATADO',
                        'DESCRIPCION DEL PLAN',
                        'ULTIMO PAGO',
                        'FECHA ULTIMO PAGO'
                    ];

                }else{
                    
                    $campos_ver = [ 
                        'MUNICIPIO',
                        'TELEFONO',
                        'VALOR TARIFA',
                        'ESTRATO',
                        'DEPARTAMENTO'
                    ];
                }
                
                foreach($campana->campos_visualizar as $campo_ver)
                {   
                    $campo[$posicion] = $campo_ver->campo;        
                    $posicion += 1;
                }
                
                $campos_faltantes = array_diff($campos_ver , $campo);

                $tipos_campos = [
                    'ARCHIVO',
                    'FECHA',
                    'NUMERICO',
                    'SELECCION_CON_MULTIPLE_RESPUESTA',
                    'SELECCION_CON_UNICA_RESPUESTA',
                    'TEXT',
                    'TEXTAREA'
                ];

                return view('adminlte::campana.edit',compact(
                    'campana',
                    'campos_faltantes',
                    'tipos_campos'
                    
                ));
            }else{
                abort(404);
            }
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
        if (Auth::user()->can('campañas-editar')) {
            // 
            $campana = Campana::find($id); 
            
            if($campana->estado != 'FINALIZADA'){

                DB::transaction(function() use($request , $id , $campana){

                    $campana->sin_restricciones = ($request->restricciones == 1)? true : false;
                    
                    //Nombre
                    if($request->nombre_campana != null){
                        $campana->nombre = $request->nombre_campana; 

                        if(!$campana->save()){
                            DB::rollBack();
                            return redirect()->route('campanas.index')->with('error', 'Error. Al guardar el Nombre.');              
                        }
                    }

                    //Campos de la campaña 
                    $campos = CampanaCampos::where('campana_id', $id)
                                            ->whereNotIn('nombre', ['Motivo_atencion', 'Categoria_atencion'])
                                            ->get();

                    foreach($campos as $campo){

                        $nombre = str_replace(" ","_",$campo->nombre); 

                        if($request[$nombre] == null){

                            $campo->estado = 0 ;

                            if(!$campo->save()){
                                DB::rollBack();
                                return redirect()->route('campanas.index')->with('error', 'Error. Con le estado de los campos.');
                            }
                        }else{                          
                            
                            $campo->estado = 1 ;

                            if(!$campo->save()){
                                DB::rollBack();
                                return redirect()->route('campanas.index')->with('error', 'Error. Con le estado de los campos.');
                            }
                        } 
                    }

                    if($request->nombres != null){

                        foreach($request->nombres as $indice => $nombre_campo){
                        
                            if($nombre_campo != null){
                                $respuesta = $this->crearCampanaCampo($nombre_campo, $request->tipo[$indice], $id , $request);

                                $campana_campo = new CampanaCampos();
                                $campana_campo->nombre = $nombre_campo;
                                $campana_campo->tipo = $request->tipo[$indice];
                                $campana_campo->estado = 1 ;
                                $campana_campo->campana_id = $id;
                            
                                if(!$respuesta){
                                    DB::rollBack();
                                    return redirect()->route('campanas.index')->with('error', 'Error. Al crear campos para la campaña.');
                                }
                            }
                                
                                                                                                            
                        } 
                    }
                                
                                
                    //campos a vizualizar--------------------------
                    $existen = [];
                    foreach($campana->campos_visualizar as $campo_ver){
                        
                        if(!empty($request->campos)){
                            if(!in_array($campo_ver->campo , $request->campos)){
                                CamposVisualizar::where([['campana_id',$id],['campo',$campo_ver->campo]])->delete();
                            }
                        }else{
                            CamposVisualizar::where([['campana_id',$id],['campo',$campo_ver->campo]])->delete();
                        }  

                        $existen [] = $campo_ver->campo;                           
                    }  
                    
                    if(!empty($request->campos)){
                        foreach( $request->campos as $campo_nu){
                        
                            if(!in_array($campo_nu , $existen)){
                
                                $camposvisualizar = new CamposVisualizar();
                                $camposvisualizar->campo = $campo_nu;
                                $camposvisualizar->campana_id = $id;
                
                                if(!$camposvisualizar->save()){
                                    DB::rollBack();
                                    return redirect()->route('campanas.index')->with('error', 'Error. Al asignar los campos a visualizar.');
                                }           
                            }
                                    
                        } 
                    }
                              
                });
                return redirect()->route('campanas.index')->with('success', 'Se actualizo campaña correctamente.');    
            }else{
                return redirect()->route('campanas.index')->with('error', 'Esta campaña no esta disponible.');    
            }

        }else{
            abort(403);
        }                
    }

    public function estado(Request $request, $id)
    {
        if (Auth::user()->can('campañas-editar')) {
            $campana = Campana::find($id); 

            if($request->accion == 'true'){
                $fecha_actual = strtotime(date("Y-m-d"));
                $fecha_inicio = strtotime($campana->fecha_inicio);  

                if($fecha_actual < $fecha_inicio){               
                    return redirect()->route('campanas.index')->with('warning', ' La campaña no esta para ejecutar Hoy'); 
                }else{
                    $campana->estado = 'EN EJECUCION';

                    if($campana->save()){
                        return redirect()->route('campanas.index')->with('success', 'La campaña ejecutada correctamente.');                      
                    }               
                } 
                        
            }else{
                $campana->estado = 'FINALIZADA';
                $campana->fecha_finalizacion = date("Y-m-d H:i:s");
            
                if($campana->save()){
                    return redirect()->route('campanas.index')->with('success', 'La campaña finalizada correctamente.');                      
                }
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
        if (Auth::user()->can('campañas-eliminar')) {
 
            $campos = CampanaCampos::where('campana_id',$id)->get();

            foreach($campos as $campo){           
                $cantidad = CampanaRespuestas::where('campo_id',$campo->id)->count();
                if($cantidad == 0){
                    CampanaRespuestas::where('campo_id',$campo->id)->delete();
                    CampanaCamposOpciones::where('campo_id',$campo->id)->delete();
                    CampanaCampos::find($campo->id)->delete();
                }else{
                    DB::rollBack();
                    return redirect()->route('campanas.index')
                    ->with('error','La campaña ya tiene informacion');         
                }
            }

            CamposVisualizar::where('campana_id',$id)->delete();

            $clientes = CampanaClientes::where('campana_id',$id)->get();
            foreach($clientes as $cliente){
                if(count($cliente->observaciones) == 0){
                    CampanaClientes::find($cliente->id)->delete();
                }else{
                    DB::rollBack();
                    return redirect()->route('campanas.index')
                    ->with('error','La campaña ya tiene informacion');    
                }
            }

            Campana::where('id',$id)->delete();
            return redirect()->route('campanas.index')
                    ->with('success','campaña eliminada correctamente');

        }else{
            abort(403);
        }
    } 
 
    public function destroyCampo($campana , $id)
    {
        $campo = CampanaCampos::where('campana_id',$campana)->with('respuestas')->find($id);
        
        if(count($campo->respuestas) == 0 ){
            $opciones = CampanaCamposOpciones::where('campo_id',$id)->get();
            if(count($opciones) > 0){
                foreach ($opciones as $opcion) {
                    $resp = $opcion->delete();                  
                    if(!$resp){
                        return response()->json('El campo no se puedo eliminar.');    
                    }
                }
            };

            return response()->json($campo->delete());  
                     
        }else{
            return response()->json('El campo no se puede eliminar ya tiene respuestas.');    
        }

    } 

    public function exportar(Request $request )
    {
        if (Auth::user()->can('campañas-exportar')) {

            $campana = Campana::find($request->campana_id);

            Excel::create('Campana '. utf8_decode($campana->nombre) , function($excel) use($campana) {

                $excel->sheet('Clientes', function($sheet) use($campana) {

                    $datos = array();

                    $contador = 0;

                    $estructura = [
                        'IDENTIFICACION' => null,
                        'NOMBRE' => null,
                        'APELLIDO' => null,
                        'PROYECTO' => null,
                        'DEPARTAMENTO' => null,
                        'MUNICIPIO' => null,

                        'TICKET' => null,
                        'MANTENIMIENTO' => null,
                        'PQR' => null,
                        'SOLICITUD' => null,

                        'TOTAL DEUDA' => null,
                        
                        'AGENTE' => null,
                        'ESTADO LLAMADA' => null,
                        'FECHA LLAMADA' => null,
                        'FECHA RELLAMAR' => null,
                        'OBSERVACIONES' => null
                        
                        
                    ];

                    foreach ($campana->campos as $pregunta) {

                        if($pregunta->nombre != 'Motivo_atencion' && $pregunta->nombre != 'Categoria_atencion'){
                            $estructura[strval($pregunta->nombre)] = null;
                        }
                    }


                    foreach($campana->clientes as $cliente_campana) {

                        $estructura = array_fill_keys(array_keys($estructura), null);

                        $estructura['IDENTIFICACION'] = $cliente_campana->cliente->Identificacion;
                        $estructura['NOMBRE'] = $cliente_campana->cliente->NombreBeneficiario;
                        $estructura['APELLIDO'] = $cliente_campana->cliente->Apellidos;
                        $estructura['PROYECTO'] = $cliente_campana->cliente->proyecto->NumeroDeProyecto;
                        $estructura['DEPARTAMENTO'] = $cliente_campana->cliente->municipio->NombreDepartamento;
                        $estructura['MUNICIPIO'] = $cliente_campana->cliente->municipio->NombreMunicipio;
                        
                        $estructura['TICKET'] = $cliente_campana->ticket_id;
                        $estructura['MANTENIMIENTO'] = $cliente_campana->mantenimiento_id;
                        $estructura['PQR'] = (!empty($cliente_campana->pqr_id))? $cliente_campana->pqr->cun : '';

                        $validar_soliditud = AtencionCliente::join('solicitudes as s', function($join){
                            $join->on('atencion_clientes.id', '=', 's.atencion_cliente_id')->where('s.estado', 'PENDIENTE');
                        })->where('atencion_clientes.cliente_id', $cliente_campana->cliente_id)->count();

                        $estructura['SOLICITUD'] = ($validar_soliditud > 0)? 'SI' : 'NO';

                        $estructura['TOTAL DEUDA'] = floatval( (isset($cliente_campana->cliente->historial_factura_pago))? $cliente_campana->cliente->historial_factura_pago->total_deuda : 0);

                        if($cliente_campana->respuestas->count() > 0){
                            $estructura['AGENTE'] = $cliente_campana->respuestas[0]->usuario->name;
                        }else if($cliente_campana->observaciones->count() > 0){
                            $estructura['AGENTE'] = $cliente_campana->observaciones[0]->usuario->name;
                        }

                        $estructura['ESTADO LLAMADA'] = $cliente_campana->estado;                        
                        $estructura['FECHA LLAMADA'] = (!empty($cliente_campana->respuestas->count() > 0))? $cliente_campana->respuestas[0]->created_at : '';

                        if($cliente_campana->estado != 'CONTESTA' and !empty($cliente_campana->fecha_hora_rellamar)){
                            $estructura['FECHA RELLAMAR'] = $cliente_campana->fecha_hora_rellamar;
                        }

                        if(empty($estructura['FECHA LLAMADA']) && $cliente_campana->observaciones->count() > 0){
                            $estructura['FECHA LLAMADA'] = $cliente_campana->observaciones[$cliente_campana->observaciones->count() -1]->created_at;
                        }

                        $estructura['OBSERVACIONES'] = str_replace(['[', ']'], ['', "\n"], implode($cliente_campana->observaciones->pluck('observacion')->toArray()));

                        if($cliente_campana->respuestas->count() > 0){
                            foreach ($cliente_campana->respuestas as $respuesta) {                            
                                $estructura[strval($respuesta->campo->nombre)] = $respuesta->respuesta;
                            }
                        }
                        

                        $datos[] = $estructura;

                        $contador++;
                    }


                    $sheet->fromArray($datos, true, 'A1', true);

                });

                
            })->export('xlsx');

        }else{
            abort(403);
        }
    }

    public function estadisticas($id) 
    {
        if (Auth::user()->can('campañas-estadisticas')) {

            /*SELECT 
                cl.estado,
                COUNT(cl.estado) as total
            FROM campanas_clientes as cl
            WHERE cl.campana_id = 17
            GROUP BY cl.estado*/

            $campaña = Campana::find($id);

            $estados = CampanaClientes::select('estado')->where('campana_id', $id)->get();

            $estados_agrupados = CampanaClientes::selectRaw('estado , COUNT(estado) as total')->where('campana_id', $id)->groupBy('estado')->get();

            $total = $estados_agrupados->sum('total');

            $llamadas_por_agente = DB::select("SELECT 
                u.id, 
                u.name, 
                count(co.id) as total_llamadas, 
                'NO EXITOSAS' as estado 
                FROM campanas_clientes AS cc
                    INNER JOIN campanas_observaciones as co ON cc.id = co.campana_cliente_id
                    INNER JOIN users as u ON co.usuario_id = u.id
                WHERE cc.campana_id = ?
                GROUP BY u.id, u.name
            
                UNION
            
                SELECT 
                    u.id, 
                    u.name, 
                    count(cr.campana_cliente_id) as total_llamadas, 
                    'EXITOSAS' as estado 
                FROM campanas_clientes AS cc
                    INNER JOIN (SELECT 
                        DISTINCT campana_cliente_id, 
                        usuario_id 
                        FROM campanas_respuestas) as cr ON cc.id = cr.campana_cliente_id
                    INNER JOIN users as u ON cr.usuario_id = u.id
                WHERE cc.campana_id = ?
                GROUP BY u.id, u.name", [$id,$id]);
            
            $chart = Charts::database($estados, 'bar', 'highcharts')
                ->colors(['#FFAB00','#40CFFF', '#FF0000','#008000', '#d2d6de', '#800080'])
                ->title('Campaña '.$campaña->nombre)
                ->elementLabel("Estados")
                ->responsive(true)
                ->groupBy('estado');

            $solicitudes = Solicitud::select('solicitudes.*')
            ->join('campanas_clientes', 'solicitudes.campana_cliente_id', '=', 'campanas_clientes.id')
            ->join('campanas', 'campanas_clientes.campana_id', '=', 'campanas.id')
            ->where('campanas.id', $id)            
            ->get();

            $total_solicitudes = count($solicitudes); //28545   20524869

            $solicitudes_creadas = Charts::database($solicitudes, 'pie', 'highcharts')
                ->colors(['#FFAB00', '#008000','#800080', '#d2d6de'])
                ->title('Solicitudes de la campaña')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('estado');

            $total_llamadas_por_agente = [];

            foreach ($llamadas_por_agente as $llamada) {

                $total_llamadas_por_agente[$llamada->id]['name'] = $llamada->name;

                switch ($llamada->estado) {
                    case 'NO EXITOSAS':
                        $total_llamadas_por_agente[$llamada->id]['no_exitosas'] = intval($llamada->total_llamadas);

                        if(!isset($total_llamadas_por_agente[$llamada->id]['exitosas'])){
                            $total_llamadas_por_agente[$llamada->id]['exitosas'] = 0;
                        }
                        break;
                    case 'EXITOSAS':
                        $total_llamadas_por_agente[$llamada->id]['exitosas'] = intval($llamada->total_llamadas);

                        if(!isset($total_llamadas_por_agente[$llamada->id]['no_exitosas'])){
                            $total_llamadas_por_agente[$llamada->id]['no_exitosas'] = 0;
                        }
                        break;
                    
                    default:                        
                        break;
                }
                
            }               


            return view('adminlte::campana.partials.estadisticas_campanas',compact(
                'chart',
                'contesta',
                'no_contesta',
                'pendientes',
                'equivocados',
                'volver_llamar',
                'no_llamar',
                'total',
                'solicitudes_creadas',
                'total_solicitudes',
                'total_llamadas_por_agente',
                'estados_agrupados'
            ));
            
        }else{
            abort(403);
        }
    }
 
    public function ajax_proyectos(Request $request){
        $proyectos = Facturacion::select('Proyectos.ProyectoId', 'Proyectos.NumeroDeProyecto')
        ->join('Proyectos', 'Facturacion.ProyectoId', '=', 'Proyectos.ProyectoID')
        ->where('Facturacion.Periodo',$request->periodo_f)
        ->distinct()
        ->get();
        
        return response()->json($proyectos);
    }

    public function ajax_consulta(Request $request){
        $respuesta = false;
        
        if($request->tipo_campana == 'CLIENTES'){
            $clientes = Cliente::select('Clientes.ClienteId','Clientes.Identificacion')
                        ->leftJoin('clientes_restricciones', 'Clientes.ClienteId', '=', 'clientes_restricciones.cliente_id')
                        ->whereNull('clientes_restricciones.cliente_id')
                        ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                        ->where(function($query) use($request ){

                            if(!empty($request->municipio)){
                                $query->where('municipio_id',$request->municipio);
                            }

                            if(!empty($request->departamento)){
                                $query->where('Municipios.DeptId',$request->departamento);
                            }

                            if(!empty($request->proyecto)){
                                $query->where('ProyectoId',$request->proyecto);
                            }

                            if(!empty($cedulas_especificas)){
                                $query->whereIn('Clientes.Identificacion',$cedulas_especificas);
                            }
                        
                        })->where(function($query) use($request ){

                            if(!empty($request->estado)){
                                $query->where('Clientes.Status', $request->estado);
                            }
                        })->count();

            if($clientes > 0){
                $respuesta = true;
            }

        }else{                                       
        
            $facturas = Facturacion::select('Facturacion.ClienteId','Facturacion.Internet','Facturacion.ValorTotal','Facturacion.Identificacion')
            ->join('Clientes', 'Facturacion.ClienteId', '=', 'Clientes.ClienteId')
            ->leftJoin('clientes_restricciones', 'Clientes.ClienteId', '=', 'clientes_restricciones.cliente_id')
            ->whereNull('clientes_restricciones.cliente_id')
            ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
            ->where(function($query) use($request){


                if(!empty($request->municipio)){
                    $query->where('Clientes.municipio_id',$request->municipio);
                }

                if(!empty($request->departamento)){
                    $query->where('Municipios.DeptId',$request->departamento);
                }

                if(!empty($request->proyecto)){
                    $query->where('Clientes.ProyectoId',$request->proyecto);

                }

                                                        
            })
            ->where('Facturacion.Periodo', $request->periodo)
            ->count();

                                            
            if($facturas > 0){
                $respuesta = true;
            }
                                                                                                                            
        }

        return response()->json($respuesta);

    }

    public function ajax_municipios(Request $request){

        $municipios = CampanaClientes::selectRaw('DISTINCT m.MunicipioId as id, m.NombreMunicipio as nombre')
                ->join('Clientes as c', 'campanas_clientes.cliente_id', '=', 'c.ClienteId')
                ->join('Municipios as m', 'c.municipio_id', '=', 'm.MunicipioId')
                ->where([
                    ['campanas_clientes.campana_id', $request->campana_id],
                    ['m.DeptId', $request->departamento_id]
                ])->get();
        
        return response()->json($municipios);
    }

    //Funcion para la creaccion de los campos en la campaña
    private function crearCampanaCampo($nombre, $tipo, $campanaId , $request)
    {

        /*--ALTER TABLE campanas_campos
        --ADD obligatorio BIT NOT NULL DEFAULT(1);
        */
        
        $nombre_con = ucfirst($nombre);

        $campanaCampo = new CampanaCampos();
        $campanaCampo->nombre = $nombre_con;
        $campanaCampo->tipo = $tipo;
        $campanaCampo->estado = 1;
        $campanaCampo->campana_id = $campanaId;
        //$campanaCampo->obligatorio = 
        
        $respuesta = $campanaCampo->save();

        if($respuesta == true){
            if($tipo == 'SELECCION_CON_MULTIPLE_RESPUESTA' or $tipo == 'SELECCION_CON_UNICA_RESPUESTA'){
                $nombre_opc = str_replace(array(' ', '.', "\n"), '_',$nombre);
                
                if($request[$nombre_opc]){
                    foreach ($request[$nombre_opc] as  $value) {
                        $opcion = new CampanaCamposOpciones;
                        $opcion->valor = $value;
                        $opcion->estado = 1;
                        $opcion->campo_id = $campanaCampo->id;

                        if(!$opcion->save()){
                            $respuesta = false;
                        }
                    }
                }else{
                    $respuesta = false;
                }

            }
        }

        return $respuesta; 
    }

    private function getCamposCliente()
    {
        return json_encode([
            'MUNICIPIO',
            'TELEFONO',
            'ESTRATO',
            'VALOR TARIFA',
            'DEPARTAMENTO'
        ]);
    }

    private function getCamposFacturacion()
    {
        return json_encode([
            'FACTURAID',
            'TELEFONOS DE CONTACTO CLIENTE',
            'PERIODO FACTURA',
            'PROYECTO',
            'SALDO A FAVOR',
            'VALOR TARIFA',
            'PLAN CONTRATADO',
            'DESCRIPCION DEL PLAN',
            'ULTIMO PAGO',
            'FECHA ULTIMO PAGO'
        ]);
    }
 
}
