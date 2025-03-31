<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Custom\ImageTextBlur;
use App\Ubicacion;
use App\Mail\Contrato;
use App\ContratoEvento;
use App\Custom\Data;
use App\ClienteContrato;
use App\ContratoServicio;
use App\ContratoArchivo;
use App\PlanComercial;
use App\Cliente;
use App\ArchivoCliente;
use App\Olt;
use App\Proyecto;
use App\Novedad;
use App\User;
use App\ProyectoDocumentacion;
use App\ProyectoPreguntaRespuesta;
use App\ReporteOntFallida;
use Storage;
use DB;
use Excel;
use Image;

class ClientesContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('contratos-listar')) {


            $estados_contratos = ClienteContrato::selectRaw('estado, COUNT(estado) as total')->groupBy('estado')->pluck('total', 'estado');

            $contratos = ClienteContrato::Cedula($request->get('documento'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->Tipo($request->get('tipo_contrato'))
            ->Estado($request->get('estado'))
            ->orderBy('id', 'DESC')
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $estados = ['VIGENTE', 'PENDIENTE', 'FINALIZADO', 'ANULADO'];
            $tipos_contratos = ['VENCIDO','ANTICIPADO'];

            return view('adminlte::contratos.index', compact('contratos', 'proyectos','estados','tipos_contratos','estados_contratos'));

        }else{
            abort(403);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Cliente $cliente)
    {
        if (Auth::user()->can('contratos-crear')) {

            $proyectos = Proyecto::select('ProyectoID as id', 'NumeroDeProyecto as nombre')->where('Status', 'A')->get();
            $estratos = [0,1,2,3,4,5,6];

            $documentos = ArchivoCliente::where('ClienteId', $cliente->ClienteId)->pluck('nombre');

            return view('adminlte::clientes.contratos.create', compact('cliente', 'proyectos', 'estratos', 'documentos'));

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
    public function store(Cliente $cliente, Request $request)
    {
        if (Auth::user()->can('contratos-crear')) {    

            //validamos que los datos requeridos no vengan vacidos
            $this->validate(request(),[

                'proyecto' => 'required',
                'estrato' => 'required',
                'fecha_inicio' => 'required',

                'plan_internet' => 'required',
          
                'correo' => 'required|email|max:255',
                'celular' => 'required',
                        
                'direccion' => 'required',
                'direccion_recibo' => 'required',
                'coordenadas' => 'required',
                'archivos.*' => 'required|mimes:jpg,jpeg,png,gif',
            ]);


            $result = DB::transaction(function () use($request, $cliente) {

                /*------------------FINALIZAR CONTRATO------------------------*/
                //validar novedades
                $validar_novedades = Novedad::where('ClienteId', $cliente->ClienteId)->whereNull("fecha_fin")->count();

                if($validar_novedades > 0){
                    return ['error', 'El cliente tiene Novedades sin Finalizar.'];
                }                
                /*------------------------------------------*/

                $cliente->ProyectoId = $request->proyecto;
                $cliente->tipo_beneficiario = $request->tipo_beneficiario;
                

                $cliente->CorreoElectronico = strtolower($request->correo);
                $cliente->TelefonoDeContactoMovil = trim(str_replace(["(", ")", "-"], "",$request->celular));                
                
                $cliente->DireccionDeCorrespondencia = $request->direccion;
                $cliente->direccion_recibo = $request->direccion_recibo;
                $cliente->Barrio = $request->barrio;

                $cliente->Estrato = $request->estrato;

                $ubicaciones = Ubicacion::select('UbicacionId')->where('MunicipioId', $request->municipio)->first();
            
                $cliente->UbicacionId = $ubicaciones->UbicacionId;

                $cliente->Status = 'PENDIENTE';

                $coordenadas = explode(',', $request->coordenadas);

                $cliente->Latitud = $coordenadas[0];
                $cliente->Longitud = $coordenadas[1];

                $planes_tarifas = PlanComercial::findOrFail($request->plan_internet);
                $cliente->ValorTarifaInternet = $planes_tarifas->ValorDelServicio;
                $cliente->PlanComercial = $request->plan_internet;
                $cliente->user_id = Auth::user()->id;

                if ($cliente->save()) {

                    /*----------------GUARDAR PREGUNTAS-----------------*/

                    if(!empty($request->respuesta)){

                        foreach ($request->respuesta as $key => $value) {

                            $respuesta = ProyectoPreguntaRespuesta::where([
                                ['proyecto_id', $request->proyecto], 
                                ['proyecto_pregunta_id', $key],
                                ['cliente_id', $cliente->ClienteId]
                            ])->first();

                            if(empty($respuesta)){
                                $respuesta = new ProyectoPreguntaRespuesta;
                                $respuesta->cliente_id = $cliente->ClienteId;
                                $respuesta->proyecto_id = $request->proyecto;
                                $respuesta->proyecto_pregunta_id = $key;
                            }

                            if(is_array($value)){
                                $respuesta->respuesta = json_encode($value, JSON_UNESCAPED_UNICODE);
                            }else{
                                $respuesta->respuesta = strval($value);
                            }

                            if(!$respuesta->save()){
                                DB::rollBack();
                                return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al guardar las respuestas de las preguntas.'];
                            }
                        }

                    }

                    $id = $cliente->ClienteId;

                    /* --------------------------------GUARDAR ARCHIVOS-----------------------------------*/
                    //Declaramos una ruta
                    $directory = 'clientes/'.$cliente->Identificacion;

                    //Si no existe el directorio, lo creamos
                    if (!file_exists($directory)) {
                        //Creamos el directorio
                        Storage::disk('public')->makeDirectory($directory);
                    }

                    if(!empty($request->archivos)){
                        foreach ($request->archivos as $key => $value) {

                            $imprimir_coordenadas = ProyectoDocumentacion::select('coordenadas')->where([['nombre', $key], ['proyecto_id', $request->proyecto]])->first();

                            $this->guardar_archivos($id, $directory, $key, $value, $coordenadas[0], $coordenadas[1], $imprimir_coordenadas->coordenadas);           
                        }
                    }

                    if(!empty($request->firma)){

                        $this->guardar_archivos($id, $directory, 'firma', $request->firma);
                    }

                    if(!empty($request->firma_usuario)){

                        $vendedor = User::find(Auth::user()->id);

                        $file = $request->firma_usuario;
                        $directory = 'usuarios/'. $vendedor->id;
                        $nombre = "firma";
                        $extension = 'jpg';
                        $tamaño = 800;

                        $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode($extension)->__toString();

                        //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                        $ruta = $directory.'/'.$nombre.'.'.$extension;

                        //Indicamos que queremos guardar un nuevo archivo en el disco local
                        //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));            
                        Storage::disk('public')->put($ruta, $file);

                        $existe = Storage::disk('public')->exists($ruta);           

                        if ($existe) {

                            $vendedor->firma = $ruta;

                            if(!$vendedor->save()){
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ['error', 'Error al guardar la firma del vendedor'];
                            }

                        }else{
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ['error', 'Error al subir la firma del vendedor'];
                        }
                    }

                    /* --------------------------------CONTRATO-----------------------------------*/

                    $proyecto = Proyecto::findOrFail($request->proyecto);
                
                    $contrato = new ClienteContrato;
                    $contrato->tipo_cobro = $proyecto->tipo_facturacion;                    
                    $contrato->vigencia_meses = $proyecto->vigencia;
                   

                    $contrato->fecha_inicio = $request->fecha_inicio;
                    $contrato->clausula_permanencia = ($proyecto->clausula->count() > 0)? true : false;
                    $contrato->estado = "PENDIENTE";
                    $contrato->vendedor_id = Auth::user()->id;
                    $contrato->ClienteId = $cliente->ClienteId;

                    if ($contrato->save()) {

                        $contrato->referencia = date('Y').'-'.$contrato->id;
                        $contrato->save();

                        #Registro de Evento
                        $evento = new ContratoEvento;
                        $evento->accion = "creó";
                        $evento->descripcion = "El usuario " . Auth::user()->name . " creó  el contrato " . $contrato->id;
                        $evento->user_id = Auth::user()->id;
                        $evento->contrato_id = $contrato->id;

                        if(!$evento->save()){
                            DB::rollBack();
                            return ['error', 'Error al crear el evento del contrato.'];
                        }

                        /* --------------------------------SERVICIO-----------------------------------*/
                        $planes_tarifas = PlanComercial::findOrFail($request->plan_internet);

                        $servicio = new ContratoServicio;
                        $servicio->nombre = $planes_tarifas->nombre;
                        $servicio->descripcion = $planes_tarifas->DescripcionPlan;
                        $servicio->cantidad = $planes_tarifas->VelocidadInternet;
                        $servicio->unidad_medida = 'Megas';
                        $servicio->valor = $planes_tarifas->ValorDelServicio;
                        $servicio->tipo_servicio = 'INTERNET';

                        if ($request->estrato < 3) {
                            $servicio->iva = false;
                        }else if ($request->estrato >= 3){
                            $servicio->iva = true;
                        }
                        
                        $servicio->estado = 'PENDIENTE';
                        $servicio->contrato_id = $contrato->id;

                        if($servicio->save()){

                            #Registro de Evento
                            $evento = new ContratoEvento;
                            $evento->accion = "creó";
                            $evento->descripcion = "Servicio #". $servicio->id ." creado por el sistema. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;
                            $evento->user_id = Auth::user()->id;
                            $evento->contrato_id = $contrato->id;

                            if(!$evento->save()){
                                DB::rollBack();
                                return ['error', 'Error al crear el evento del servicio.'];
                            }

                            return ['success', 'Contrato creado correctamente.'];
                            
                        }else{
                            DB::rollBack();
                            return ['error', 'Error al crear el servicio!'];
                        }

                    }else{
                        DB::rollBack();
                        return ['error', 'Error al crear el contrato!'];
                    }

                }else{
                    DB::rollBack();
                    return ['error', 'Error al actualizar el cliente!'];
                }
            });

            return response()->json(['tipo_mensaje' => $result[0], 'mensaje' => $result[1]]);

            return redirect()->route('clientes.show', $request->cliente_id)->with($result[0], $result[1]);

           
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
    public function show(Cliente $cliente, $id)
    {
        if (Auth::user()->can('contratos-ver')) {

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!in_array($cliente->ProyectoId, $array)){
                    abort(403);
                }                
            }

            $contrato = ClienteContrato::findOrFail($id);
            $estados_servicio = array('Activo', 'Inactivo', 'Suspendido');
            $olts = Olt::where('municipio_id', $contrato->cliente->municipio_id)->get();

            $por_difrutar = '';

            if(!empty($contrato->fecha_final)){

                $fecha_inicio = (!empty($contrato->fecha_operacion))? $contrato->fecha_operacion : $contrato->fecha_fecha_instalacion;
                $fecha_estimada = date('Y-m-d', strtotime($fecha_inicio . ' + ' . $contrato->vigencia_meses . ' month'));

                $date1 = new \DateTime($contrato->fecha_final);
                $date2 = new \DateTime($fecha_estimada);

                $diff = $date1->diff($date2);
                $por_difrutar = ($diff->m) + ($diff->y * 12) . ' Meses y ' . $diff->d .' Días';

                if($fecha_estimada <= date('Y-m-d')){
                    $por_difrutar = '0 Meses y 0 Días';
                }

            }

            

            $lista_archivos = ['contrato','acta_jurametada'];

            $planes_generales = PlanComercial::select('PlanId', 'nombre', 'ValorDelServicio', 'DescripcionPlan', 'Estrato', 'TipoDePlan', 'VelocidadInternet')->where([['TipoDePlan','GENERAL'],['Status', 'A'], ['ProyectoId', $contrato->cliente->ProyectoId]]);

            $planes = PlanComercial::select('PlanId', 'nombre', 'ValorDelServicio', 'DescripcionPlan', 'Estrato', 'TipoDePlan', 'VelocidadInternet')->where([['Estrato',$contrato->cliente->Estrato],['Status', 'A'],['ProyectoId', $contrato->cliente->ProyectoId]])->union($planes_generales)->orderBy('nombre')->get();

            $fsp = null; $ontid = null;
            $reporteOntFallido = ReporteOntFallida::where('ClienteId', '=', $contrato->cliente->ClienteId);
            if ($reporteOntFallido->exists()) {
                $reporteOntFallido = $reporteOntFallido->first();
            } elseif (isset($contrato->cliente->cliente_ont_olt) ) {
                try { 
                    $reporteOntFallido = null;
                    $ip = $contrato->cliente->cliente_ont_olt->olt->ip;
                    $usuario = $contrato->cliente->cliente_ont_olt->olt->usuario;
                    $pass = Crypt::decrypt($contrato->cliente->cliente_ont_olt->olt->password);//'Web*-*("#)126Q';
                    
                    #Informacion ONT
                    $client = new \Bestnetwork\Telnet\TelnetClient($ip);
                    $client->login($usuario, $pass);

                    $command = 'enable';
                    $client->execute($command);

                    $command = 'config';
                    $client->execute($command);

                    $i = 2;
                    try {
                        if($contrato->cliente->cliente_ont_olt->olt->version == 2){
                            $i = 3;
                            $command = 'display ont info by-sn ' . $contrato->cliente->cliente_ont_olt->activo->Serial;
                            $client->execute($command, ":");
                            $resp = $client->execute("\r\n", "---- More ( Press 'Q' to break ) ----");
                        }else{
                            $command = 'display ont info by-sn ' . $contrato->cliente->cliente_ont_olt->activo->Serial;
                            $resp = $client->execute($command, "---- More ( Press 'Q' to break ) ----");
                        }
                    } catch (\Bestnetwork\Telnet\TelnetException $th) {
                        error_log($th->getMessage());
                        $reporteOntFallido = $th->getMessage();
                    } 
                    
                    $client->disconnect();

                    $response = explode("\r\n", $resp);

                    $ont = array();

                    for ($i; $i < count($response); $i++) { 
                        $datos = explode(" : ", $response[$i]);
                        $ont[str_replace('  ', '', $datos[0])] = $datos[1];                                      
                    }
                    /*--------------------------------------------------------*/

                    $fsp = str_replace(' ', '',$ont["F/S/P"]);
                    $ontid = str_replace(' ', '',$ont["ONT-ID "]); 

                }catch (\Exception $e) {
                    $ont['error'] = $e->getMessage();
                }
            } else {
                $reporteOntFallido = null;
            }

            return view('adminlte::contratos.show', compact(
                'contrato',
                'ont',
                'estados_servicio',
                'olts',
                'fsp', 
                'ontid', 
                'planes',
                'lista_archivos', 
                'reporteOntFallido', 
                'por_difrutar'
            ));

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
    public function edit(Cliente $cliente, $id)
    {
        if (Auth::user()->can('contratos-editar')) { 
            $contrato = ClienteContrato::findOrFail($id);
            $estados = array('PENDIENTE', 'VIGENTE', 'SUSPENDIDO', 'FINALIZADO', 'ANULADO');
            $tipos_cobro = array('VENCIDO','ANTICIPADO', 'NO APLICA');
            return view('adminlte::contratos.edit', compact('contrato', 'estados', 'tipos_cobro'));
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
    public function update(Request $request, Cliente $cliente , $id)
    {
        if (Auth::user()->can('contratos-editar')) {

            $this->validate(request(),[
                'referencia' => 'required',
                'tipo_cobro' => 'required',
                'vigencia' => 'required',            
                'fecha_inicio' => 'required',            
                'estado' => 'required'
            ]);

            $result = DB::transaction(function () use($request, $id) {

                $contrato = ClienteContrato::find($id);
                $contrato->referencia = $request->referencia;
                $contrato->tipo_cobro = $request->tipo_cobro;
                $contrato->vigencia_meses = $request->vigencia;
                $contrato->fecha_inicio = $request->fecha_inicio;
                $contrato->fecha_instalacion = $request->fecha_instalacion;
                $contrato->fecha_final = $request->fecha_final;
                $contrato->observacion = $request->observacion;
                $contrato->fecha_operacion = $request->fecha_operacion;

                $clausula = $request->clausula;
                $aplica_cliente = $request->status;

                if ($clausula == 'on') {
                    $contrato->clausula_permanencia = true;
                }else{
                    $contrato->clausula_permanencia = false;
                }
            
                $contrato->estado = $request->estado;

                $cliente = Cliente::find($contrato->ClienteId);

                if ($request->estado == 'FINALIZADO' || $request->estado == 'ANULADO') {

                    /*$suspensiones_temporales = $cliente->suspensiones_temporales->whereIn('estado', ['ACTIVA', 'PENDIENTE'])->count();

                    if($suspensiones_temporales > 0){
                        DB::rollBack();
                        return ['warning', 'El cliente tiene suspensiones temporales Activas o Pendiente, debe finalizarlas primero.'];
                    }*/



                    /*------------------FINALIZAR CONTRATO------------------------*/

                    $validar_novedades = Novedad::where('ClienteId', $cliente->ClienteId)->whereNull("fecha_fin")->count();

                    if($request->estado == 'FINALIZADO' && $aplica_cliente == 'on'){

                        $novedades_pendientes_finalizar = Novedad::where('ClienteId', $cliente->ClienteId)->whereNull("fecha_fin")->get();

                        if($novedades_pendientes_finalizar->count() > 0){

                            foreach ($novedades_pendientes_finalizar as $novedad) {

                                $novedad->fecha_fin = date("Y-m-d H:i:s");

                               if(!$novedad->save()){
                                    DB::rollBack();
                                    return ['error', 'Error al finalizar la novedad'];
                                }
                            }
                        }
                        

                    }else{

                        if($validar_novedades > 0){
                            return ['error', 'El cliente tiene Novedades sin Finalizar.'];
                        }

                    }

                    

                    $novedades_pendientes = Novedad::where([['ClienteId', $contrato->ClienteId],['cobrar', false]])->whereBetween("fecha_fin", [date('Y-m') . "-01 00:00:00", date('Y-m-d') . " 23:59:59"])->get();                

                    if(!empty($novedades_pendientes)){

                        $servicio_actual = $contrato->servicio()->first();

                        $tarifa = $servicio_actual->valor;

                        foreach ($novedades_pendientes as $novedad_pendiente) {

                            $valor_cobrar = 0;
                            $cantidad = 0;

                            $desde = new \DateTime($novedad_pendiente->fecha_inicio);
                            $hasta = new \DateTime($novedad_pendiente->fecha_fin);

                            if (date('m', strtotime($novedad_pendiente->fecha_inicio)) <> date('m')){
                                $desde = new \DateTime(date('Y-m') . "-01 00:00:00");
                            }

                            // Obtener las marcas de tiempo
                            $timestamp1 = $desde->getTimestamp();
                            $timestamp2 = $hasta->getTimestamp();

                            $diferencia = $timestamp2 - $timestamp1;

                            switch ($novedad_pendiente->unidad_medida) {                                
                                case 'MINUTOS':
                                    $cantidad = $diferencia / 60;
                                    $valor_cobrar = (($tarifa / 30) / 24) / 24;
                                    break;
                                case 'HORAS':
                                    $cantidad = $diferencia / 3600;
                                    $valor_cobrar = ($tarifa / 30) / 24;
                                    break;
                                case 'DIAS':
                                    $cantidad = $diferencia / 86400;
                                    $valor_cobrar = $tarifa / 30;
                                    break;
                                default:
                                    return ['error', 'Unidad de medida no programada!'];
                                    break;
                            }

                            $conceptos_descuento = ['Suspensión por Mora', 'Suspensión Temporal', 'Ajustes por falta de servicio'];                            
                            
                            $novedad_pendiente->cantidad = $cantidad;
                            $novedad_pendiente->valor_unidad = (in_array($novedad_pendiente->concepto, $conceptos_descuento))? round(($valor_cobrar * -1),2)  : round($valor_cobrar,2);
                            $novedad_pendiente->cobrar = true;

                            if(!$novedad_pendiente->save()){
                                DB::rollBack();
                                return ['error', 'Error al recalcular las novedades.'];
                            }
                        }
                    }

                    /*------------------------------------------*/                   
                    

                    foreach ($contrato->servicio as $servicio) {

                        $servicio->estado = 'Inactivo';

                        if(!$servicio->save()){
                            DB::rollBack();
                            return ['error', 'Error al inactivar los servicios.'];
                        }
                    }
                    
                    $cliente->EstadoDelServicio = "Inactivo";
                    $cliente->FechaFinDelServicio = $request->fecha_final;

                    if ($aplica_cliente == 'on' || $request->estado == 'FINALIZADO') {
                        $cliente->Status = 'INACTIVO';
                    }


                }elseif ($request->estado == 'VIGENTE') {
                    if ($aplica_cliente == 'on') {
                        $cliente->EstadoDelServicio = "Activo";
                        $cliente->Status = 'ACTIVO';
                    }

                    foreach ($contrato->servicio as $servicio) {

                        if ($servicio->estado == 'PENDIENTE') {

                            $servicio->estado = 'Activo';

                            if(!$servicio->save()){
                                DB::rollBack();
                                return ['error', 'Erro al activar los servicios.'];
                            }
                        }                
                    }
                }else{
                    $cliente->Fecha = $request->fecha_inicio;
                }

                if($cliente->save()){
                    #Registro de Evento
                    $evento = new ContratoEvento;
                    $evento->accion = $request->estado;
                    $evento->descripcion = 'Se cambio el estado a '.$request->estado . " el contrato #". $contrato->id;
                    $evento->user_id = Auth::user()->id;
                    $evento->contrato_id = $contrato->id;

                    if(!$evento->save()){
                        DB::rollBack();
                        return ['error', 'Error al crear el evento.'];
                    }
                }else{
                    DB::rollBack();
                    return ['error', 'Error al actualizar el cliente.'];
                }            

                if ($contrato->save()) {

                    /*if (!empty($cliente->softv)) {
                    DB::statement('exec actualizar_vigencia_contrato_soft ?,?', [$cliente->softv->id_softv, $request->vigencia_softv]);
                    }*/

                    return ['success', 'Contrato actualziado correctamente.'];

                }else{
                    DB::rollBack();
                    return ['error', 'Error al actualizar el contrato.'];
                }
            });

            return redirect()->route('clientes.contratos.show', [$cliente->ClienteId, $id])->with($result[0], $result[1]);

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
    public function destroy(Cliente $cliente, $id)
    {
        if (Auth::user()->can('contratos-eliminar')) { 

            $contrato = ClienteContrato::findOrFail($id);

            $cliente_id = $contrato->ClienteId;

            foreach ($contrato->servicio as $servicio) {
               $servicio->delete();
            }

            foreach ($contrato->evento as $evento) {
               $evento->delete();
            }

            if($contrato->delete()){

                if (Storage::disk('public')->exists($contrato->archivo)){
                    Storage::disk('public')->delete($contrato->archivo);
                }
                
                $directory = 'contratos\\'.$id;

                //validamos si el directorio existe
                if (Storage::disk('public')->exists($directory)) {
                    //eliminamos la carpeta con su contenido
                    Storage::disk('public')->deleteDirectory($directory);
                }

                return redirect()->route('clientes.show', $cliente_id)->with('success','Contrato eliminado.');
            }else{
                return redirect()->route('clientes.show', $cliente_id)->with('error','No se pudo eliminar.');
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
    public function sendContrato(Request $request)
    {
        if (Auth::user()->can('contrato-enviar')) {
            $this->validate(request(),[
                'contrato_id' => 'required',
                'archivos' => 'required',
                'correo' => 'required'
                ]);  

            $correo_cliente = $request->correo;

            $data = new Data;
            $data_contrato = $data->contrato($request->contrato_id);
            

            /*foreach ($request->archivos as $archivo) {            
                if ($archivo == 'Contrato') {
                    $data['contrato_pdf'] = $this->contrato_generar('S', $data);
                }elseif ($archivo == 'Acta Juramentada') {
                    $data['declaracion_pdf'] = $this->declaracion_generar('S', $data);
                }elseif ($archivo == 'Acta de instalacion') {
                    # code...
                }
            }  */

            //dd($request->archivos);        

            //Mail::to($correo_cliente)->send(new Contrato($data_contrato, $request->archivos));

            #Registro de Evento
            $evento = new ContratoEvento;
            $evento->accion = "envió";
            $evento->descripcion = "Se envió PDF del contrato #". $request->contrato_id ." y sus actas, y se envió declaración juramantada.";
            $evento->user_id = Auth::user()->id;
            $evento->contrato_id = $request->contrato_id;
            $evento->save();

            return redirect()->route('contratos.show', $request->contrato_id)->with('success','Contrato enviado.');

        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){
        if ($request->ajax()) {
            $contratos = ClienteContrato::selectRaw('clientes_contratos.id, clientes_contratos.tipo_cobro, clientes_contratos.vigencia_meses, clientes_contratos.fecha_operacion, clientes_contratos.fecha_instalacion, clientes_contratos.fecha_final, clientes_contratos.estado, SUM(contratos_servicios.valor) as total')
            ->join('contratos_servicios', function($join){
                $join->on('clientes_contratos.id','contratos_servicios.contrato_id')
                    ->whereNotIn('contratos_servicios.estado',['Finalizado']);
                })
            ->where('ClienteId', $request->cliente_id)
            ->groupBy(['clientes_contratos.id','tipo_cobro','vigencia_meses','fecha_operacion', 'fecha_instalacion','fecha_final','clientes_contratos.estado'])
            ->get();
            return response()->json($contratos);
        }
    }



    public function exportar(Request $request){

        if (Auth::user()->can('contratos-exportar')) {

            Excel::create('contratos', function($excel) use($request) {
    
                $excel->sheet('Contratos', function($sheet) use($request) {

                
                    $datos = array();

                    $contratos = ClienteContrato::Cedula($request->get('documento'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Tipo($request->get('tipo_contrato'))
                    ->Estado($request->get('estado'))
                    ->orderBy('id', 'DESC')
                    ->get();                

                    foreach ($contratos as $contrato) {
                        $datos[] = array(
                            "CEDULA" => $contrato->cliente->Identificacion,
                            "NOMBRE" => $contrato->cliente->NombreBeneficiario. ' ' .$contrato->cliente->Apellidos,
                            "ESTADO CLIENTE" => $contrato->cliente->Status,
                            "MUNICIPIO" => $contrato->cliente->municipio->NombreMunicipio,
                            "DEPARTAMENTO" => $contrato->cliente->municipio->departamento->NombreDelDepartamento,
                            "REFERENCIA" => $contrato->referencia,
                            "TIPO DE COBRO" => $contrato->tipo_cobro,
                            "VIGENCIA - MESES" => $contrato->vigencia_meses,                        
                            "FECHA INICIO" => $contrato->fecha_inicio,
                            "FECHA INSTALACION" => $contrato->fecha_instalacion,
                            "FECHA OPERACION" => $contrato->fecha_operacion,
                            "FECHA FINALIZACIÓN" => $contrato->fecha_final,
                            "TIENE CLAUSULA" => $contrato->clausula_permanencia,
                            "ESTADO" => $contrato->estado,
                            "VENDEDOR" => $contrato->vendedor->name,
                            "OBSERVACIONES" => $contrato->observacion
                        );
                    }

                    

                    if (count($datos) == 0) {
                        return redirect()->route('contratos.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    $sheet->fromArray($datos, true, 'A1', true);
    
                });
            })->export('xlsx');
            
        }else{
            abort(403);
        }
    }


    private function guardar_archivos($cliente_id, $directory, $nombre, $file, $latitud = null, $longitud = null, $imprimir = false){
        $tamaño = 1500;

        if (!empty($file)) {

            $archivo = ArchivoCliente::where([['ClienteId', $cliente_id],['nombre', $nombre]])->first();

            if(!empty($archivo)){

                //Si el archivo ya existe lo eliminamos para reemplazarlo por el nuevo
                if (Storage::disk('public')->exists($archivo->archivo)){
                    Storage::disk('public')->delete($archivo->archivo);
                }

            }else{
                $archivo = new ArchivoCliente;
            }

            

            if ($nombre == 'firma'){
                $extension = 'jpg';
                $tamaño = 800;

            }else{
                //Obtenemos el tipo de archivo que se esta subiendo
                $extension = strtolower($file->getClientOriginalExtension());
            }
                        
            $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($extension)->__toString();

            //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
            $ruta = $directory.'/'.$nombre.'.'.$extension;

            //Indicamos que queremos guardar un nuevo archivo en el disco local
            //Storage::put('public/' . $nombre.'.'.$extension, \File::get($value));            
            Storage::disk('public')->put($ruta, $file);

            $existe = Storage::disk('public')->exists($ruta);           

            if ($existe) {

                if($imprimir){
                    $this->estampar_coordenadas($ruta, $ruta, $latitud, $longitud);
                }

                $archivo->nombre = $nombre;
                $archivo->archivo = $ruta;
                $archivo->tipo_archivo = $extension;
                $archivo->estado = 'EN REVISION';
                $archivo->ClienteId = $cliente_id;

                if(!$archivo->save()){
                    DB::rollBack();
                    Storage::disk('public')->delete($ruta);
                    return ['error', 'Error al guardar los archivos'];
                }
            }else{
                DB::rollBack();
                Storage::disk('public')->delete($ruta);
                return ['error', 'Error al guardar los archivos'];
            }

        }        
    }

    private function estampar_coordenadas($archivo, $destino, $latitud, $longitud){

        $path = Storage::disk('public')->path($archivo);
       
        $im = imagecreatefromjpeg($path);
        $fecha = date('Y-m-d');

        $font             = "C:\Windows\Fonts\arial.ttf";
        $width            = imagesx($im);
        $height           = imagesy($im) - 40;
        $string = "Fecha: $fecha \nLatitud: $latitud, Longitud: $longitud";

        // set our image's colors
        $text_color       = imagecolorallocate($im, 255, 255, 255);
        $shadow_color     = imagecolorallocate($im, 0x00, 0x00, 0x00);

        $imagenblur = new ImageTextBlur;



        // place the shadow onto our image
        $imagenblur->imagettftextblur(
            $im, 18, 0, 20, $height - 7,
            $shadow_color,
            $font,
            $string,
            10
        );

        // place the text onto our image
        $imagenblur->imagettftextblur(
            $im, 18, 0, 20, $height - 7,
            $text_color,
            $font,
            $string
        );


        imagejpeg($im, $path);
    }
    
}
