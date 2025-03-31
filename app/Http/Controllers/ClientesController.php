<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\ImageTextBlur;
use App\Cliente;
use App\PlanComercial;
use App\Municipio;
use App\Ubicacion;
use App\Departamento;
use App\ArchivoCliente;
use App\ClienteOntOlt;
use App\Proyecto;
use App\Olt;
use App\ClienteContrato;
use App\ContratoServicio;
use App\User;
use App\Recaudo;
use App\Facturacion;
use App\Novedad;
use App\Ticket;
use App\Mantenimiento;
use App\ProyectoPregunta;
use App\ProyectoPreguntaRespuesta;
use App\ProyectoDocumentacion;
use App\ProyectoTipoBeneficiario;
use App\Instalacion;
use App\MantenimientoCliente;
use Storage;
use Image;
use Excel;
use Zipper;
use DB;

use App\Traits\Planes;


class ClientesController extends Controller
{
    use Planes;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = '';

        if (Auth::user()->can('clientes-listar')) {

            if (Auth::user()->hasRole('vendedor')) {
                $clientes = Cliente::with('ubicacion')
                ->Palabra($request->get('palabra'))
                ->Proyecto($request->get('proyecto'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->Estado($request->get('estado'))
                ->where([['Status','RECHAZADO'], ['user_id', Auth::user()->id]])            
                ->paginate(15);

            }else{
                $clientes = Cliente::with('ubicacion')            
                ->Palabra($request->get('palabra'))
                ->Proyecto($request->get('proyecto'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->Estado($request->get('estado'))
                ->Accion($request->get('accion'))
                ->where(function ($query) {
                    if(Auth::user()->proyectos()->count() > 0){
                        $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->paginate(15);
            }       

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
            ->where(function ($query) {
                if(Auth::user()->proyectos()->count() > 0){
                    $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                }
            })
            ->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $estados = ["ACTIVO","INACTIVO", "EN INSTALACION", "PENDIENTE", "RECHAZADO"];
            return view('adminlte::clientes.index', compact('clientes', 'proyectos', 'departamentos', 'estados'));
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
        if (Auth::user()->can('clientes-crear')) {
            //$departamentos = Departamento::where('Status', 'A')->orderBy('NombreDelDepartamento', 'ASC')->get();
            //$planes_tarifas = PlanComercial::where([['Status', 'A'],['ProyectoId', 6]])->get();
            $proyectos = Proyecto::select('ProyectoID as id', 'NumeroDeProyecto as nombre')->where('Status', 'A')->get();
            $zonas = ['RURAL', 'URBANA'];
            $localidades = ['CORREGIMIENTO', 'INSPECCIÓN','SECTOR-URBANO','VEREDA'];

            return view('adminlte::clientes.create', compact('proyectos','zonas','localidades'));
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
        if (Auth::user()->can('clientes-crear')) {
            //validamos que los datos requeridos no vengan vacidos
            $this->validate(request(),[

                'proyecto' => 'required',
                'departamento' => 'required',
                'municipio' => 'required',
                'estrato' => 'required',

                'plan_internet' => 'required',

                'tipo_documento' => 'required',
                'documento' => 'required',
                'lugar_expedicion' => 'required',
                'fecha_nacimiento' => 'required',
                'nombres' => 'required',
                'apellidos' => 'required',
                'lugar_nacimiento' => 'required',
                'genero' => 'required',
                'sexo' => 'required',
                'orientacion_sexual' => 'required',
                'correo' => 'required|email|max:255',
                'celular' => 'required',
                'etnia' => 'required',
                'nivel_estudios' => 'required',
                'discapacidad' => 'required',
                        
                'direccion' => 'required',
                'direccion_recibo' => 'required',
                'coordenadas' => 'required',
                'tipo_vivienda' => 'required',

                'firma' => 'required',
                'archivos.*' => 'required',
            ]);
            
            $result = DB::transaction(function () use($request) {

                //asignamos todos los datos que se envian por post
                $cliente = new Cliente;
                $cliente->tipo_beneficiario = $request->tipo_beneficiario;
                $cliente->TipoDeDocumento = $request->tipo_documento;
                $cliente->Identificacion = $request->documento;
                $cliente->ExpedidaEn = mb_convert_case($request->lugar_expedicion, MB_CASE_TITLE, "UTF-8");
                $cliente->NombreBeneficiario = mb_convert_case($request->nombres, MB_CASE_TITLE, "UTF-8");
                $cliente->Apellidos = mb_convert_case($request->apellidos, MB_CASE_TITLE, "UTF-8");
                $cliente->genero = $request->genero;
                $cliente->fecha_nacimiento = $request->fecha_nacimiento;
                $cliente->lugar_nacimiento = mb_convert_case($request->lugar_nacimiento, MB_CASE_TITLE, "UTF-8");

                $cliente->pertenencia_etnica = $request->etnia;
                $cliente->sexo = $request->sexo;
                $cliente->orientacion_sexual = $request->orientacion_sexual;
                $cliente->nivel_estudios = $request->nivel_estudios;
                $cliente->discapacidad = $request->discapacidad;
                
                $cliente->TelefonoDeContactoFijo = $request->telefono;
                $cliente->TelefonoDeContactoMovil = $request->celular;
                $cliente->CorreoElectronico = strtolower($request->correo);       
                
                $cliente->DireccionDeCorrespondencia = $request->direccion;
                $cliente->direccion_recibo = $request->direccion_recibo;
                $cliente->Barrio = $request->barrio;
                $cliente->NombreEdificio_o_Conjunto = $request->urbanizacion;
                $cliente->zona = $request->zona;
                $cliente->localidad = $request->localidad;
                $cliente->Estrato = $request->estrato;
                $cliente->RelacionConElPredio = $request->tipo_vivienda;
                $cliente->municipio_id = $request->municipio;

                $ubicaciones = Ubicacion::select('UbicacionId')->where('MunicipioId', $request->municipio)->first();
                
                $cliente->UbicacionId = $ubicaciones->UbicacionId;

                $cliente->Status = 'PENDIENTE';

                $coordenadas = explode(',', $request->coordenadas);

                $cliente->Latitud = $coordenadas[0];
                $cliente->Longitud = $coordenadas[1];           

                $cliente->user_id = Auth::user()->id;
                $cliente->ProyectoId = $request->proyecto;
                $cliente->PlanComercial = $request->plan_internet;
                $cliente->AutorizaFacturaElectronica = 'SI';
                $cliente->EmpresaFacturaID = 1;
                $cliente->Fecha = date('Y-m-d');

                //Valor de la tarifa de internet
                $planes_tarifas = PlanComercial::findOrFail($request->plan_internet);
                $cliente->ValorTarifaInternet = $planes_tarifas->ValorDelServicio;


                if ($cliente->save()) {

                    /*----------------GUARDAR PREGUNTAS-----------------*/

                    

                    if(!empty($request->respuesta)){

                        $info = array();

                        foreach ($request->respuesta as $key => $value) {

                            $pregunta = ProyectoPregunta::select('opciones_respuesta')->findorFail($key);

                            if(!empty($pregunta->opciones_respuesta)){
                                $array_opciones = json_decode($pregunta->opciones_respuesta);                               

                                if(!in_array($value, $array_opciones)){                                   
                                    DB::rollBack();
                                    return ['tipo_mensaje' => 'error', 'mensaje' => 'Error. las preguntas no fueron respondidas en su totalidad.'];
                                }
                            }

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
                    $directory = 'clientes/'.$request->documento;

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
                    
                    $contrato = new ClienteContrato;                
                    $contrato->tipo_cobro = $cliente->proyecto->tipo_facturacion;

                    if(!empty($cliente->proyecto->fecha_fin_proyecto)){

                        $date1 = new \DateTime();
                        $date2 = new \DateTime($cliente->proyecto->fecha_fin_proyecto);

                        $diff = $date1->diff($date2);

                        $vigencia = ($diff->m) + ($diff->y * 12);

                        if($vigencia == 0){
                            $vigencia = 1;
                        }
            
                        $contrato->vigencia_meses = $vigencia + 1;

                    }else{
                        $contrato->vigencia_meses = $cliente->proyecto->vigencia;
                    }

                    $contrato->fecha_inicio = date('Y-m-d');
                    $contrato->clausula_permanencia = ($cliente->proyecto->clausula->count() > 0)? true : false;
                    $contrato->estado = "PENDIENTE";
                    $contrato->vendedor_id = Auth::user()->id;
                    $contrato->ClienteId = $cliente->ClienteId;                

                    if ($contrato->save()) {

                        $contrato->referencia = date('Y').'-'.$contrato->id;
                        $contrato->save();

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
                            return ['tipo_mensaje' => 'success', 'mensaje' => 'Cliente creado correctamente.'];
                            
                        }else{
                            DB::rollBack();
                            return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al crear el servicio!'];
                        }

                    }else{
                        DB::rollBack();
                        return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al crear el contrato!']; 
                    }

                }else{
                    DB::rollBack();
                    return ['tipo_mensaje' => 'error', 'mensaje' => 'Error al crear el cliente!'];  
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
        if (Auth::user()->can('clientes-ver')) {
            #Actualizar Municipios
            /*$clientes = Cliente::select('ClienteId', 'UbicacionId')->with('Ubicacion')->where('UbicacionId', 93)->whereNull('municipio_id')->limit(500)->get();

            foreach ($clientes as $dato ) {
                $cliente = Cliente::find($dato->ClienteId);
                $cliente->municipio_id = $dato->ubicacion->municipio->MunicipioId;
                $cliente->save();
            }*/

            $cliente = null;

            /*if (Auth::user()->hasRole('vendedor')) {

                $cliente = Cliente::where([['user_id', Auth::user()->id], ['Status', 'RECHAZADO']])->findOrFail($id);

                if (empty($cliente)) {
                    abort(403);
                }


            }else{
                
            }*/

            $cliente = Cliente::findOrFail($id);

            $mantenimimientos_masivos = MantenimientoCliente::with('mantenimiento')->where('ClienteId', $cliente->ClienteId)
            ->whereHas('mantenimiento', function ($query){
                $query->where('Mantenimientos.estado', '!=', 'CERRADO');
            })->get();
            
           

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!in_array($cliente->ProyectoId, $array)){
                    abort(403);
                }
            }
            

            

            // funcion en la base de datos llamada historial_factura_pago_cliente que recibe como parametro el id del cliente y retorna la informacion en tabla de la reclacion de las facturas generados y los pagos efectuados para el cliente a consultar.
            $recaudos = DB::select('SELECT * from historial_factura_pago_cliente(?) order by fecha asc', [$id]);
                
            $motivos_rechazo = array('Documentacion Incompleta','Firma no Corresponde','No aplica Subsidio','Direccion no Corresponde', 'Foto fachazada sin identificar');        

            $vendedores = User::whereHas('roles',function($q){
                                $q->where('roles.name', '=', 'vendedor');})->orderBy('name','ASC')->get();

            $planes = $this->listar($cliente->ProyectoId, $cliente->Estrato, $cliente->municipio_id);

            return view('adminlte::clientes.show', compact('cliente', 'motivos_rechazo', 'vendedores', 'planes', 'recaudos', 'mantenimimientos_masivos'));
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
        if (Auth::user()->can('clientes-actualizar')) {

            $cliente = Cliente::findOrFail($id);
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::where('Status', 'A')->get();
            $tipo_beneficiario = (isset($cliente->proyecto->tipos_beneficiarios)) ? $cliente->proyecto->tipos_beneficiarios : array();
            $genero = array(array('sigla' => 'M' , 'valor' => 'Masculino' ),
                            array('sigla' => 'F' , 'valor' => 'Femenino' ),
                            array('sigla' => 'T' , 'valor' => 'Transgénero')
                            );
            $etnia = array('Mulato','Indigena','Negra','Afro','Palenquera','Raizal','Gitanos - Rom','Mestiza','Sin Informacion');
            $sexo = array('Hembra', 'Macho','Intersexual', 'Sin informacion');
            $orientacion_sexual = array('Heterosexual', 'Homosexual', 'Bisexual', 'Sin informacion');
            $nivel_estudios = array('Preescolar', 'Basica','Media', 'Superior pregrado', 'Superior posgrado','Sin informacion');
            $discapacidad = array('Visual', 'Auditiva', 'Fisica', 'Cognitiva-Intelectual', 'Psicosocial', 'Multiple', 'Sin discapacidad');
            $tipo_vivienda = array('Arrendada', 'Familiar', 'Propia');
            $estados = array('ACTIVO', 'INACTIVO','EN INSTALACION','TRASLADO','PENDIENTE', 'RECHAZADO');
            $zonas = ['RURAL', 'URBANA'];
            $localidades = ['CORREGIMIENTO', 'INSPECCIÓN','SECTOR-URBANO','VEREDA'];
            
            $clasificacion = array('WISPER',' NO SUBSANABLE', 'CASMOT', 'DIALNET');

            $estratos = [0,1,2,3,4,5,6];

            return view('adminlte::clientes.edit', compact(
                'cliente', 
                'proyectos', 
                'departamentos', 
                'tipo_beneficiario', 
                'genero', 
                'etnia', 
                'sexo',
                'orientacion_sexual',
                'nivel_estudios', 
                'discapacidad', 
                'tipo_vivienda', 
                'estados',
                'clasificacion', 
                'zonas',
                'localidades',
                'estratos'
            ));

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
    public function subsanar(Request $request, $id)
    {
        if (Auth::user()->can('clientes-subsanar')){
            $this->validate(request(),[
                'estado' => 'required'
            ]);
            
            if ($request->estado == 'PENDIENTE') {

                $pendientes = ArchivoCliente::where([['estado', 'RECHAZADO'], ['ClienteId', $id]])->count();

                if ($pendientes == 0) {
                    $cliente = Cliente::find($id);
                    $cliente->Status = $request->estado;

                    if($cliente->save()){
                        return redirect()->route('clientes.index')->with('success', 'información Actualizada');
                    }else{
                        return redirect()->route('clientes.show', $id)->with('success', 'Error al actualizar estado del cliente!');
                    }
                }else{
                    return redirect()->route('clientes.show', $id)->with('warning', 'Aún tiene archivos por subsanar.');
                }
                
            }else{
                return redirect()->route('clientes.show', $id)->with('error', 'El estado seleccionado debe ser PENDIENTE.');
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

        if (Auth::user()->can('clientes-actualizar')) {

            $this->validate(request(),[
                'nombres' => 'required',
                'apellidos' => 'required',
                'lugar_expedicion' => 'required',            
                'genero' => 'required',
                'fecha_nacimiento' => 'required',
                'lugar_nacimiento' => 'required',          
                'celular' => 'required',            
                'CorreoElectronico' => 'required|email',            
                'direccion' => 'required',            
                'estrato' => 'required',
                'tipo_vivienda' => 'required',
                'municipio' => 'required',
                
                'etnia' => 'required',
                'sexo' => 'required',
                'orientacion_sexual' => 'required',
                'nivel_estudios' => 'required',
                'discapacidad' => 'required'            
            ]);

            //asignamos todos los datos que se envian por post
            $cliente = Cliente::find($id);
            $cliente->NombreBeneficiario = mb_convert_case($request->nombres, MB_CASE_TITLE, "UTF-8");
            $cliente->Apellidos = mb_convert_case($request->apellidos, MB_CASE_TITLE, "UTF-8");
            $cliente->tipo_beneficiario = $request->tipo_beneficiario;
            
            $cliente->ExpedidaEn = mb_convert_case($request->lugar_expedicion, MB_CASE_TITLE, "UTF-8");
            
            $cliente->genero = $request->genero;
            $cliente->fecha_nacimiento = $request->fecha_nacimiento;
            $cliente->lugar_nacimiento = mb_convert_case($request->lugar_nacimiento, MB_CASE_TITLE, "UTF-8");

            $cliente->pertenencia_etnica = $request->etnia;
            $cliente->sexo = $request->sexo;
            $cliente->orientacion_sexual = $request->orientacion_sexual;
            $cliente->nivel_estudios = $request->nivel_estudios;
            $cliente->discapacidad = $request->discapacidad;
            
            $cliente->TelefonoDeContactoFijo = $request->telefono;
            $cliente->TelefonoDeContactoMovil = $request->celular;
            $cliente->CorreoElectronico = strtolower($request->CorreoElectronico);

            if (!empty($request->direccion)) {
                $cliente->DireccionDeCorrespondencia = $request->direccion;
            }

            $cliente->direccion_recibo = $request->direccion_recibo;
            
            
            $cliente->Barrio = $request->barrio;
            $cliente->NombreEdificio_o_Conjunto = $request->urbanizacion;
            $cliente->zona = $request->zona;
            $cliente->localidad = $request->localidad;

            $cliente->Estrato = $request->estrato;
            $cliente->RelacionConElPredio = $request->tipo_vivienda;
            $cliente->municipio_id = $request->municipio;

            if (Auth::user()->hasRole(['admin', 'aux-desarrollo'])){
                $cliente->Status = $request->estado;
            }


            if (!empty($request->coordenadas)) {
                $coordenadas = explode(',', $request->coordenadas);

                $cliente->Latitud = $coordenadas[0];
                $cliente->Longitud = $coordenadas[1];
            } 

            $cliente->ProyectoId = $request->proyecto;
            $cliente->Clasificacion = $request->clasificacion;     


            if ($cliente->save()) {
                return redirect()->route('clientes.show', $id)->with('success', 'Cliente actualizado');
            }else{
                return redirect()->route('clientes.show', $id)->with('error', 'Error al actualizar');
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
        //
    }

    public function ajaxValidar(Request $request){
        if ($request->ajax()) {
            $cliente = Cliente::select('ClienteId')->where(function($query) use ($request){
                if(isset($request->cliente_id)){
                    $query->where($request->validar, $request->cedula)->whereNotIn('ClienteId', [$request->cliente_id]);
                }else{
                    $query->where($request->validar, $request->cedula);
                }

            })->count();
            return response()->json($cliente);
        }  
    }

    public function ajax(Request $request){
        if ($request->ajax()) {
            $data = Cliente::where('Identificacion', $request->cedula)->first();


            $cliente = array();

            if (count($data) > 0) {

                $novedades = Novedad::where('ClienteId', $data->ClienteId)->whereNull('fecha_fin')->get();
                $ticket = Ticket::where('ClienteId', $data->ClienteId)->whereNotIn('EstadoDeTicket', array(0))->first();
                $mantenimiento = Mantenimiento::select('MantId','NumeroDeTicket','TipoMantenimiento')->where('Estado', 'Abierto')->Cedula($request->cedula)->first();

                if (count($mantenimiento) == 0) {
                    $mantenimiento = Mantenimiento::select('Mantenimientos.MantId','Mantenimientos.NumeroDeTicket','Mantenimientos.TipoMantenimiento')
                    ->join('MantenimientoProgramacionClientes','Mantenimientos.MantId','MantenimientoProgramacionClientes.Mantid')
                    ->join('Clientes', 'MantenimientoProgramacionClientes.ClienteId','=','Clientes.ClienteId')

                    ->where([['Mantenimientos.Estado', 'Abierto'],['Clientes.Identificacion', $request->cedula]])
                    ->first();
                }

                $cliente['id'] = $data->ClienteId;
                $cliente['cedula'] = $data->Identificacion;
                $cliente['nombre'] = mb_convert_case($data->NombreBeneficiario . ' ' . $data->Apellidos, MB_CASE_TITLE, "UTF-8");
                $cliente['correo'] = $data->CorreoElectronico;
                $cliente['direccion'] = $data->DireccionDeCorrespondencia.' - '. $data->Barrio . ' - ' . $data->municipio->NombreMunicipio.' - '. $data->municipio->NombreDepartamento;
                $cliente['municipio'] = $data->municipio->NombreMunicipio;
                $cliente['departamento'] = $data->municipio->NombreDepartamento;
                $cliente['departamento_id'] = $data->municipio->DeptId;
                $cliente['municipio_id'] = $data->municipio_id;
                $cliente['proyecto'] = $data->proyecto->NumeroDeProyecto;
                $cliente['estado'] = $data->Status;
                $cliente['telefono'] = $data->TelefonoDeContactoMovil;
                $cliente['novedades'] = $novedades;
                $cliente['ticket'] = (!empty($ticket))? $ticket->TicketId : null;
                $cliente['otro'] = count($mantenimiento);
                $cliente['mantenimiento'] = $mantenimiento;
                $cliente['total_deuda'] = '$'. number_format( (empty($data->historial_factura_pago) ? 0 : $data->historial_factura_pago->total_deuda), 0,',','.');
            }

            return response()->json($cliente);
        }
    }

    private function guardar_archivos($cliente_id, $directory, $nombre, $file, $latitud = null, $longitud = null, $imprimir = false){
        $tamaño = 1500;

        if (!empty($file)) {
            

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
                    $this->estampar_coordenadas($ruta, $ruta, $latitud, $longitud, $extension);
                }

                $archivo = new ArchivoCliente;
                $archivo->nombre = $nombre;
                $archivo->archivo = $ruta;
                $archivo->tipo_archivo = $extension;
                $archivo->estado = 'EN REVISION';
                $archivo->ClienteId = $cliente_id;

                if(!$archivo->save()){
                    DB::rollBack();
                    Storage::disk('public')->deleteDirectory($directory);
                    return ['error', 'Error al guardar los archivos'];
                }
            }else{
                DB::rollBack();
                Storage::disk('public')->deleteDirectory($directory);
                return ['error', 'Error al guardar los archivos'];
            }

        }        
    }

    private function estampar_coordenadas($archivo, $destino, $latitud, $longitud, $extension){

        $path = Storage::disk('public')->path($archivo);

        $im = null;

        if($extension == "png"){
            $im = imagecreatefrompng($path);
        }else{
            $im = imagecreatefromjpeg($path);
        }      
       
        
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

    public function vistaImportar(){

        if (Auth::user()->can('clientes-importar')){
            return view('adminlte::clientes.importar');
        }else{
            abort(403);
        }

    }

    public function importar(Request $request){

        if (Auth::user()->can('clientes-importar')){

            $this->validate($request,[                
                'data' => 'required|mimes:csv,txt|max:100000',
                'data_preguntas' => 'required|mimes:csv,txt|max:100000',
                'archivo_carpetas' => 'required|mimes:zip'
            ]);


            $data_clientes = $this->procesar_txt($request->file('data'));

            $result = DB::transaction(function () use($data_clientes, $request) {

                Zipper::make($request->archivo_carpetas)->extractTo(public_path('storage\\carpetas\\'. Auth::user()->id ));

                $resultado = [
                    'cargados' => 0,
                    'existentes' => 0,
                    'cedulas_existente' => null,
                    'sin_archivos' => 0,
                    'cedulas_sin_archivos' => null
                ];

                foreach($data_clientes as $data_cliente){

                    $validar = Cliente::select('ClienteId')->where('Identificacion', $data_cliente['cedula'])->count();

                    if($validar > 0){
                        $resultado['existentes'] += 1;
                        $resultado['cedulas_existente'][] = $data_cliente['cedula'];
                    }else{
                        
                        $planes_tarifas = PlanComercial::find($data_cliente['plan_comercial_id']);
                        $beneficiario = ProyectoTipoBeneficiario::findOrFail($data_cliente['tipo_beneficiario']);                    

                        $cliente = new Cliente;
                        $cliente->tipo_beneficiario = $beneficiario->nombre;
                        $cliente->TipoDeDocumento = $data_cliente['tipo_documento'];
                        $cliente->Identificacion = $data_cliente['cedula'];
                        $cliente->ExpedidaEn = mb_convert_case($data_cliente['lugar_expedicion'], MB_CASE_TITLE, "UTF-8");
                        $cliente->NombreBeneficiario = mb_convert_case($data_cliente['nombres'], MB_CASE_TITLE, "UTF-8");
                        $cliente->Apellidos = mb_convert_case($data_cliente['apellidos'], MB_CASE_TITLE, "UTF-8");
                        $cliente->fecha_nacimiento = date("Y-m-d", strtotime($data_cliente['fecha_nacimiento']));
                        $cliente->lugar_nacimiento = mb_convert_case($data_cliente['lugar_nacimiento'], MB_CASE_TITLE, "UTF-8");

                        $cliente->pertenencia_etnica = $data_cliente['pertenencia_etnica'];
                        $cliente->genero = $data_cliente['genero'];

                        $cliente->sexo = $data_cliente['sexo'];
                        $cliente->orientacion_sexual = $data_cliente['orientacion_sexual'];
                        $cliente->nivel_estudios = $data_cliente['nivel_estudios'];
                        $cliente->discapacidad = $data_cliente['discapacidad'];
                        $cliente->TelefonoDeContactoFijo = $data_cliente['telefono'];
                        $cliente->TelefonoDeContactoMovil = $data_cliente['celular'];
                        $cliente->CorreoElectronico = strtolower($data_cliente['correo']);
                        
                        $cliente->DireccionDeCorrespondencia = $data_cliente['direccion_casa'];
                        $cliente->direccion_recibo = $data_cliente['direccion_recibo'];
                        $cliente->Barrio = mb_convert_case($data_cliente['barrio'], MB_CASE_TITLE, "UTF-8");
                        $cliente->NombreEdificio_o_Conjunto = mb_convert_case($data_cliente['urbanizacion'], MB_CASE_TITLE, "UTF-8");
                        $cliente->zona = $data_cliente['zona'];
                        $cliente->localidad = $data_cliente['localidad'];
                        $cliente->Estrato = $data_cliente['estrato'];
                        $cliente->RelacionConElPredio = $data_cliente['tipo_vivienda'];
                        $cliente->municipio_id = $data_cliente['municipio_id'];
                        
                        $ubicacion = Ubicacion::select('UbicacionId')->where('MunicipioId', $data_cliente['municipio_id'])->first();            
                        $cliente->UbicacionId = $ubicacion->UbicacionId;
                        
                        $cliente->ProyectoId = $data_cliente['proyecto_id'];
                        
                        $cliente->Latitud = $data_cliente['latitud'];
                        $cliente->Longitud = $data_cliente['longitud'];
                        $cliente->SabeFirmar = ($data_cliente['sabe_firmar'] == "SI")? 1 : 0;
                        $cliente->user_id = $data_cliente['user_id'];
                        $cliente->Status = 'PENDIENTE';

                        $cliente->PlanComercial = $data_cliente['plan_comercial_id'];
                        $cliente->ValorTarifaInternet = $planes_tarifas->ValorDelServicio;
                        $cliente->EstadoDelServicio = 'Inactivo';

                        $cliente->Fecha = $data_cliente['fecha'];

                        $cliente->AutorizaFacturaElectronica = 'SI';
                        $cliente->EmpresaFacturaID = 1;
                        $cliente->EstadoDelServicio = 'Inactivo';
                        
                        if($cliente->save()){
                            $directory_zip = 'carpetas\\'. Auth::user()->id . '\\clientes\\' . $data_cliente['_id'];

                            if (Storage::disk('public')->exists($directory_zip)) {

                                //$directory = "clientes/". $cliente->ClienteId;
                                $directory = "clientes/". $cliente->Identificacion;

                                #GUARDAR FOTOS
                                
                                #Movemos la carpeta del cliente a la ruta final
                                Storage::disk('public')->move($directory_zip, $directory);

                                #Retorna la lista de archivos que contiene la carpeta
                                $files = Storage::disk('public')->files($directory);

                                #Recorremos el array e insertamos en la base de datos el nombre y ruta de los archivos que contiene la carpeta.
                                foreach($files as $foto){                            
                                    $array = explode("/", $foto);

                                    $nombre = explode('.', $array[2]);

                                    $archivo = new ArchivoCliente;
                                    $archivo->nombre = str_replace("_"," ",$nombre[0]);
                                    $archivo->archivo = $foto;
                                    $archivo->tipo_archivo = $nombre[1];
                                    $archivo->estado = 'EN REVISION';
                                    $archivo->ClienteId = $cliente->ClienteId;

                                    if(!$archivo->save()){
                                        DB::rollBack();
                                        
                                        if (!Storage::disk('public')->exists($directory)) {
                                            Storage::disk('public')->deleteDirectory($directory);
                                        }

                                        return ['result' => 'error', 'message' => 'Error al guardar el registro de la imagen'];
                                    }
                                }

                                Storage::disk('public')->deleteDirectory($directory_zip);

                            }else{
                                $resultado['sin_archivos'] += 1;
                                $resultado['cedulas_sin_archivos'][] = $data_cliente['cedula'];
                            }
                            
                            #GUARDAR PREGUNTAS
                            $data_respuestas_preguntas = $this->procesar_txt($request->file('data_preguntas'), $data_cliente['_id']);

                            
                            
                            foreach ($data_respuestas_preguntas as $pregunta_respuesta) {

                                $pregunta = ProyectoPregunta::select('opciones_respuesta')->findorFail($pregunta_respuesta['proyecto_pregunta_id']);

                                if(!empty($pregunta->opciones_respuesta)){
                                    $array_opciones = json_decode($pregunta->opciones_respuesta);                               
                                    
                                    $pregunta_respuesta['respuesta'] = trim(str_replace("\\", "", $pregunta_respuesta['respuesta']));

                                    if(!in_array($pregunta_respuesta['respuesta'], $array_opciones)){                                   
                                        DB::rollBack();

                                        if (!file_exists($directory)) {
                                            Storage::disk('public')->deleteDirectory($directory);
                                        }
                                        
                                        return ['result' => 'error', 'message' => 'Error. las preguntas no fueron respondidas en su totalidad. Pregunta: ' . $pregunta_respuesta['proyecto_pregunta_id'] . ' cliente_id: ' . $data_cliente['_id']];
                                    }
                                }

                                $respuesta = new ProyectoPreguntaRespuesta;
                                $respuesta->cliente_id = $cliente->ClienteId;
                                $respuesta->proyecto_id = $pregunta_respuesta['proyecto_id'];
                                $respuesta->proyecto_pregunta_id = $pregunta_respuesta['proyecto_pregunta_id'];
                                $respuesta->respuesta = $pregunta_respuesta['respuesta'];

                                if(!$respuesta->save()){
                                    DB::rollBack();

                                    if (!file_exists($directory)) {
                                        Storage::disk('public')->deleteDirectory($directory);
                                    }

                                    return ['result' => 'error', 'message' => 'Error al guardar las respuestas de las preguntas!'];

                                }
                                
                            }
                            

                            #CONTRATO
                            $contrato = new ClienteContrato;
                            $contrato->referencia = null;
                            $contrato->tipo_cobro = $cliente->proyecto->tipo_facturacion;
                            $contrato->vigencia_meses = $cliente->proyecto->vigencia;
                            $contrato->fecha_inicio = $data_cliente['fecha'];
                            $contrato->clausula_permanencia = $cliente->proyecto->clausula_permanencia;
                            $contrato->estado = 'PENDIENTE';
                            $contrato->vendedor_id = $data_cliente['user_id'];
                            $contrato->ClienteId = $cliente->ClienteId;

                            if ($contrato->save()) {

                                $contrato->referencia = date('Y').'-'.$contrato->id;
                                $contrato->save();

                                #CREAMOS EL SERVICIO ASOCIADO AL CONTRATO
                                $servicio = new ContratoServicio;
                                $servicio->nombre = $planes_tarifas->nombre;
                                $servicio->descripcion = $planes_tarifas->DescripcionPlan;
                                $servicio->cantidad = $planes_tarifas->VelocidadInternet;
                                $servicio->unidad_medida = 'Megas';
                                $servicio->valor = $planes_tarifas->ValorDelServicio;
                                $servicio->tipo_servicio = 'INTERNET';
                                $servicio->estado = 'PENDIENTE';
                                $servicio->contrato_id = $contrato->id;

                                if ($data_cliente['estrato'] < 3) {
                                    $servicio->iva = false;
                                }else if ($data_cliente['estrato'] >= 3){
                                    $servicio->iva = true;
                                }
                                
                                if(!$servicio->save()){
                                    DB::rollBack();

                                    if (!file_exists($directory)) {
                                        Storage::disk('public')->deleteDirectory($directory);
                                    }

                                    return ['result' => 'error', 'message' => 'Error al crear el servicio!'];
                                }

                            }else{
                                DB::rollBack();

                                if (!file_exists($directory)) {
                                    Storage::disk('public')->deleteDirectory($directory);
                                }

                                return ['result' => 'error', 'message' => 'Error al crear el contrato!'];
                            }

                            //exitoso
                            $resultado['cargados'] += 1;

                        }else{
                            //error al guardar el cliente
                            DB::rollBack();
                            return ['result' => 'error', 'message' => 'Error al crear el cliente!'];
                        }
                    }

                }

                Storage::disk('public')->deleteDirectory('carpetas\\'. Auth::user()->id);

                return $resultado;               

            });

            dd($result);
            
        }else{
            abort(403);
        }
    }

    public function exportar(Request $request){
        
        if (Auth::user()->can('clientes-exportar')) {

            $datos = null;

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!empty($request->get('proyecto'))){
                    
                    if(!in_array($request->get('proyecto'), $array)){
                        abort(403);
                    }
                }    
                

                $datos = $this->excel_interventoria($request);

            }elseif ($request->formato == 'INTERVENTORIA') {
                $datos = $this->excel_interventoria($request);
            }else{
                $datos = $this->excel_amigored($request);
            }
            
            Excel::create('clientes', function($excel) use($request, $datos) {
                $excel->sheet('Clientes', function($sheet) use($request, $datos) {                        
                    //$sheet->fromArray($datos, null, 'A0', false, false);        
                    $sheet->fromArray($datos, true, 'A1', true);                        
                });                    
            })->export('xlsx');

        }else{
            abort(403);
        }
    }

    private function ultimo_pago2($cliente_id){

        $pago = Recaudo::select('valor','Fecha')
        ->where('ClienteId', $cliente_id)       
        ->orderBy('RecaudoId','DESC')
        ->first();

        $resultado = array('valor' => 0, 'fecha' => null);

        if (count($pago) > 0) {
            $resultado['valor'] = floatval($pago->valor);
            $resultado['fecha'] = $pago->Fecha;
        }

        return $resultado;
    }

    private function excel_amigored($request){       

        $datos = array();

        $clientes = Cliente::selectRaw("
            Clientes.ClienteId,
            Proyectos.NumeroDeProyecto, 
            Clientes.tipo_beneficiario, 
            Clientes.Clasificacion, 
            Clientes.TipoDeDocumento, 
            Clientes.Identificacion, 
            Clientes.NombreBeneficiario,
            Clientes.Apellidos,
            Clientes.TelefonoDeContactoFijo,
            Clientes.TelefonoDeContactoMovil,
            Clientes.CorreoElectronico, 
            Clientes.genero,
            Clientes.Estrato, 
            DireccionDeCorrespondencia,
            Clientes.Barrio, 
            Clientes.NombreEdificio_o_Conjunto,
            Municipios.NombreMunicipio,
            Municipios.NombreDepartamento, 
            Municipios.region,
            Clientes.Latitud, 
            Clientes.Longitud, 
            Clientes.Status as estado,
            Clientes.EstadoDelServicio as estado_servicio,
            metas.nombre as meta, 
            metas_clientes.idpunto, 
            contratos_servicios.nombre as plan_comercial, 
            contratos_servicios.descripcion as descripcion_plan, 
            contratos_servicios.valor, 
            users.name as vendedor, 
            clientes_contratos.fecha_inicio, 
            clientes_contratos.fecha_instalacion,
            clientes_contratos.fecha_operacion,
            clientes_contratos.vigencia_meses, 
            clientes_contratos.estado as estado_contrato, 
            ActivosFijos.Serial, 
            historial_factura_pagoV.total_deuda, 
            ultima_facturaV.periodo as ultimo_periodo")
        ->leftJoin('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
        ->join('clientes_contratos', 'Clientes.ClienteId', '=', 'clientes_contratos.ClienteId', 'left outer')
        ->leftJoin('metas_clientes','Clientes.ClienteId', '=','metas_clientes.ClienteId')
        ->leftJoin('metas', 'metas_clientes.meta_id','=','metas.id')
        ->leftJoin('contratos_servicios', 'clientes_contratos.id', '=', 'contratos_servicios.contrato_id')
        ->join('Proyectos', 'Proyectos.ProyectoID', '=', 'Clientes.ProyectoId')
        ->leftJoin('users', 'Clientes.user_id', '=', 'users.id')
        ->leftJoin('historial_factura_pagoV', 'Clientes.ClienteId', '=', 'historial_factura_pagoV.ClienteId')
        ->leftJoin('clientes_onts_olts', 'Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
        ->leftJoin('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
        ->leftJoin('ultima_facturaV', 'Clientes.ClienteId', '=', 'ultima_facturaV.cliente_id')
        ->Cedula($request->get('documento'))
        ->Proyecto($request->get('proyecto'))
        ->Departamento($request->get('departamento'))
        ->Municipio($request->get('municipio'))
        ->Estado($request->get('estado'))
        ->Accion($request->get('accion'))
        ->get();

        foreach ($clientes as $cliente) {

            $ultimo_pago = $this->ultimo_pago2($cliente->ClienteId);
            $fecha_ultima_suspension = Novedad::select('fecha_inicio')->where([['concepto', 'Suspensión por Mora'], ['ClienteId', $cliente->ClienteId]])->whereNull('fecha_fin')->first();

                $datos[] = array(
                'CLIENTE ID' => $cliente->ClienteId,
                'PROYECTO' => $cliente->NumeroDeProyecto,
                'TIPO BENEFICIARIO' => $cliente->tipo_beneficiario,
                'CLASIFICACION' => $cliente->Clasificacion,
                'TIPO DOCUMENTO' => $cliente->TipoDeDocumento,
                'IDENTIFICACION' => $cliente->Identificacion,
                'NOMBRE' => $cliente->NombreBeneficiario,
                'APELLIDO' => $cliente->Apellidos,
                'TELEFONO' => $cliente->TelefonoDeContactoFijo,
                'CELULAR' => $cliente->TelefonoDeContactoMovil,
                'CORREO' => $cliente->CorreoElectronico,
                'GENERO' => $cliente->genero,
                'ESTRATO' => $cliente->Estrato,
                'DIRECCION' => $cliente->DireccionDeCorrespondencia,
                'BARRIO' => $cliente->Barrio,
                'EDIFICIO' => $cliente->NombreEdificio_o_Conjunto,
                'MUNICIPIO' => $cliente->NombreMunicipio,
                'DEPARTAMENTO' => $cliente->NombreDepartamento,
                'REGION' => $cliente->region,
                'LATITUD' => $cliente->Latitud,
                'LONGITUD' => $cliente->Longitud,
                'ESTADO CLIENTE' => $cliente->estado,
                'ESTADO SERVICIO' => $cliente->estado_servicio,
                'META' => $cliente->meta,
                'ID-PUNTO' => $cliente->idpunto,
                'PLAN COMERCIAL' => $cliente->plan_comercial,
                'DESCRIPCION' => $cliente->descripcion_plan,
                'VALOR' => $cliente->valor,
                'ESTADO CONTRATO' => $cliente->estado_contrato,
                'VENDEDOR' => $cliente->vendedor,
                'FECHA INICIO' => $cliente->fecha_inicio,
                'FECHA INSTALACION' => $cliente->fecha_instalacion,
                'FECHA OPERACION' => $cliente->fecha_operacion,

                'FECHA ULTIMA SUSPENSION' => (!empty($fecha_ultima_suspension))? $fecha_ultima_suspension->fecha_inicio : '',
                'VIGENCIA' => $cliente->vigencia_meses,
                'SERIAL' => strtoupper($cliente->Serial),
                'TOTAL DEUDA' => floatval((empty($cliente->total_deuda))? 0.00: $cliente->total_deuda),
                'ULTIMO PERIODO FACTURADO' => $cliente->ultimo_periodo,
                'ULTIMO PAGO' => $ultimo_pago['valor'],
                'FECHA ULTIMO PAGO' => $ultimo_pago['fecha']
            );

        }
            


        return $datos;

    }

    private function excel_interventoria($request){

        $datos = array();

        $clientes = Cliente::
        Cedula($request->get('documento'))
        ->Proyecto($request->get('proyecto'))
        ->Departamento($request->get('departamento'))
        ->Municipio($request->get('municipio'))
        ->Estado($request->get('estado'))
        ->where(function ($query) {
            if(Auth::user()->proyectos()->count() > 0){
                $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
            }
        })
        ->get();

        foreach ($clientes as $cliente) {

            
            $novedad = "INGRESO";
            $serial = null;

            $estado = strtoupper($cliente->EstadoDelServicio);

            $contrato = ClienteContrato::where([['ClienteId', $cliente->ClienteId], ['estado', '<>', 'ANULADO']])->first();

            if($cliente->Clasificacion == "NO SUBSANABLE"){
                $estado = "RECHAZADO";
            }elseif (isset($cliente->reemplazo)) {
                //if(isset($cliente->meta_cliente->reemplazo)){
                    $novedad = "SUSTITUTO";

                //}
            }else{

                if($cliente->Status == 'INACTIVO'){
                    $novedad = "RETIRO";
                }

                $estado = $cliente->Status;
                
            }

            if(isset($cliente->cliente_ont_olt)){
                $serial = $cliente->cliente_ont_olt->activo->Serial;
            }else if(isset($cliente)){
                $instalacion = Instalacion::where('ClienteId', $cliente->ClienteId)->orderBy('id', 'DESC')->first();

                if(!empty($instalacion)){
                    $serial = $instalacion->serial_ont;
                }
            }

            

            
           

            $data = array(
                'IdUnico' => (isset($cliente->meta_cliente))? $cliente->meta_cliente->idpunto : ((isset($cliente->reemplazo))? $cliente->reemplazo->meta_cliente->idpunto : ''),
                'IdCuenta' => $cliente->ClienteId,
                'Estado del servicio' => $estado,
                'Nombre' => $cliente->NombreBeneficiario,
                'Apellidos' => $cliente->Apellidos,
                'TipoDocumento' => $cliente->TipoDeDocumento,
                'No Documento' => $cliente->Identificacion,
                'Telefono' => (!empty($cliente->TelefonoDeContactoFijo))? floatval(str_replace(" ", "", $cliente->TelefonoDeContactoFijo)) : 0,
                'Celular' => (!empty($cliente->TelefonoDeContactoMovil))? floatval(str_replace(" ", "", $cliente->TelefonoDeContactoMovil)) : 0,
                'Correo Electrónico' => $cliente->CorreoElectronico,
                'Estrato' => $cliente->Estrato,
                'FechaInstalacion' => (!empty($contrato->fecha_instalacion))? date('d-m-Y', strtotime($contrato->fecha_instalacion)) : "",
                'fechafinoperacion' => (!empty($contrato->fecha_final))? date('d-m-Y', strtotime($contrato->fecha_final)) : "",
                'DANE Departamento' => $cliente->municipio->departamento->CodigoDaneDepartamento,
                'Departamento' => $cliente->municipio->departamento->NombreDelDepartamento,
                'DANE Municipio' => $cliente->municipio->departamento->CodigoDaneDepartamento . $cliente->municipio->CodigoDaneMunicipio,
                'Municipio' => $cliente->municipio->NombreMunicipio,
                'Localidad' => $cliente->localidad,
                'Direccion' => $cliente->direccion_recibo,
                'Barrio' => $cliente->Barrio,
                'Latitud' => $cliente->Latitud,
                'Longitud' => $cliente->Longitud,
                'Caracterizacion (SI/NO Comunidad de Conectividad)' => ($cliente->tipo_beneficiario == "Comunidad de Conectividad")? "SI" : "NO",
                'Serial' => $serial,
                'Tipo usuario' => "OBLIGATORIO",
                'Novedad' => $novedad,
            );

            $preguntas = [];

            if($cliente->proyecto->preguntas->count() > 0){
                foreach ($cliente->proyecto->preguntas as $pregunta) {

                    if($pregunta->id == 17 || $pregunta->id == 18){

                    }else{
                        $preguntas[strval($pregunta->pregunta)] = null;
                    }
                    
                }
            }

            if($cliente->proyectos_preguntas_respuestas->count() > 0){
                foreach ($cliente->proyectos_preguntas_respuestas as $respuesta) {

                    if($respuesta->proyecto_pregunta_id == 17 || $respuesta->proyecto_pregunta_id == 18){

                    }else{
                        $preguntas[strval($respuesta->pregunta->pregunta)] = ($respuesta->pregunta->tipo== "number")? intval($respuesta->respuesta) : $respuesta->respuesta;

                    }
                    
                }
            }

            $datos[] = array_merge($data, $preguntas);

        }
            


        return $datos;

    }

    private function procesar_txt($data, $id = null){

        $lineas = file($data->getPathname(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $encabezados = [];
        $datos = [];

        foreach ($lineas as $index => $linea) {
            $columnas = explode("\t", $linea);
            if ($index === 0) {
                $encabezados = $columnas; // Primera línea: encabezados
            } else {

                if(!empty($id)){
                    $fila = array_combine($encabezados, $columnas);
                    if ($fila['cliente_id'] == $id) {
                        $datos[] = $fila;
                    }
                }else{
                    $datos[] = array_combine($encabezados, $columnas); // Asociar columnas con encabezados
                }
                
            }
        }

        return $datos;
    }
}
