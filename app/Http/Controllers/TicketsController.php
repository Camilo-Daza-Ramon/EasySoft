<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ticket;
use App\TicketMedioAtencion;
use App\TipoFallo;
use App\TicketTipoPrueba;
use App\Proyecto;
use App\EstadoTicket;
use App\TicketPrueba;
use App\PQR;
use App\Mantenimiento;
use App\MantenimientoCliente;
use App\MantenimientoDireccion;
use App\Novedad;
use App\User;
use App\Departamento;
use Excel;
use DB;
use Storage;
class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $tickets_1 = Ticket::where([
            ['EstadoDeTicket',6]
        ])->whereNull('FechaCierre')->whereNotNull('ClienteId')->orderBy('TicketId', 'DESC')->get();

        $graficar = array();
        $titulo = '';
        $singeo = array();

        foreach ($tickets_1 as $dato) {

            if (!empty($dato->cliente->Latitud) && is_numeric($dato->cliente->Latitud)) {

                if ($dato->EstadoDeTicket = 6) {
                    $titulo = 'En Mantenimiento';
                }

                $graficar[] = array(
                    'titulo' => $titulo,
                    'ticket' => $dato->TicketId,
                    'latitud' => $dato->cliente->Latitud, 
                    'longitud' => $dato->cliente->Longitud, 
                    'fecha' => date('Y-m-d', strtotime($dato->FechaApertura)), 
                    'municipio' => $dato->cliente->municipio->NombreMunicipio,
                    'direccion' =>  $dato->cliente->DireccionDeCorrespondencia
                );
            }else{
                $singeo[] = array('cedula' => $dato->cliente->Identificacion);
            }

        }

        $graficar = json_encode($graficar);

        //return view('adminlte::tickets.index', ['tickets' => $tickets, 'graficar' => json_encode($graficar)]);



        if (Auth::user()->can('tickets-listar')) {        

            $tickets = Ticket::
            Cedula($request->get('documento'))
            ->Ticket($request->get('ticket'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->Estado($request->get('estado'))
            //->whereNotIn('EstadoDeTicket', array(0))
            ->orderBy('EstadoDeTicket', 'DESC')
            ->orderBy('FechaApertura', 'ASC')
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();


            $estados = EstadoTicket::get();

            return view('adminlte::soporte-tecnico.tickets.index', compact('tickets', 'proyectos', 'estados','graficar','departamentos'));
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

        if (Auth::user()->can('tickets-crear')) {
            $this->validate(request(),[
                'pruebas' => 'required',
                'canal_atencion' => 'required',
                'tipo_falla' => 'required',
                'cliente_id' => 'required',
                'prioridad' => 'required'
            ]);
        
            if ($request->ajax()) {

                $result = DB::transaction(function () use($request) {

                    $hora_apertura = date("Y-m-d H:i:s"); 
                    
                    $ticket = new Ticket;
                    $ticket->TipoDeEntrada = $request->canal_atencion;
                    $ticket->FechaApertura = date('Y-m-d H:i:s', strtotime($hora_apertura));
                    $escalado = filter_var($request->escalar_mantenimiento, FILTER_VALIDATE_BOOLEAN);

                    if ($escalado) {
                        $ticket->EstadoDeTicket = 6;
                    }else{
                        //$ticket->EstadoDeTicket = 1; VALIDAR EL ESTADO
                        $ticket->EstadoDeTicket = 0;
                    }

                    $ticket->ClienteId = $request->cliente_id;
                    $ticket->CodigoTipoDeFallo = $request->tipo_falla;
                    $ticket->Clasificacion = 0;
                    $ticket->TipoDeTicket = "TR03";
                    $ticket->Observacion = $request->descripcion;
                    $ticket->SeAfectoServicio = "S";
                    $ticket->PrioridadTicket = $request->prioridad;
                    $ticket->UserId = 1258;
                    $ticket->Escalado = ($escalado) ? 1 : 0;
                    $ticket->ResponsableFallo = 1;
                    $ticket->FechaEscalado = ($escalado) ? date('Y-m-d H:i:s') : null;

                    //$ticket->HoraApertura = $request->hora_apertura;
                    $ticket->user_crea = Auth::user()->id;

                    $directory = null;

                    if ($ticket->save()) {

                        //Declarar ruta para la imagen
                        $directory = 'tickets/'.$ticket->TicketId ;

                        //Declaramos el archivo
                        $imagen = $request->file('ImagenTicket');

                        //Asignamos el nombre al archivo
                        $nombre = 'Imagen_Ticket_'.$ticket->TicketId;


                        //Si no existe el directorio, lo creamos
                        if (!file_exists($directory)) {
                            //Creamos el directorio
                            Storage::makeDirectory('public/'.$directory);
                        }

                        //Obtenemos el tipo de archivo que se esta subiendo
                        $extension = strtolower($request->file('ImagenTicket')->getClientOriginalExtension());

                        //declaramos la ruta del archivo
                        $ruta_imagen = $directory.'/'.$nombre.'.'.$extension;

                        //Indicamos que queremos guardar un nuevo archivo en el directorio publico
                        //Storage::put('public/' .$ruta_archivo_soporte, \File::get($file));
                        Storage::put('public/'.$ruta_imagen, \File::get($imagen));

                        //validamos si el archivo se ha guardado correctamente
                        //$existe = Storage::disk('public')->exists($ruta_archivo_soporte);
                        $existe = Storage::exists('public/'.$ruta_imagen);

                        if($existe){
                            $ticket_e = Ticket::find($ticket->TicketId);
                            $ticket_e->ImagenTicket = $ruta_imagen;

                            if(!$ticket_e->save()){
                                DB::rollBack();
                                return ["error", "Error al Guardar la Imagen del Ticket", []];
                            }

                        }else{
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ["error", "Error al Guardar la Imagen del Ticket", []];
                        }                     
                        
                        #Recorremos cada pruebas
                        foreach ($request->pruebas as $prueba) {

                            $ticket_pruebas = new TicketPrueba;
                            $ticket_pruebas->TicketId = $ticket->TicketId;
                            $ticket_pruebas->PruebaId = $prueba["prueba"];
                            $ticket_pruebas->Observacion = $prueba["observacion"];
                            $ticket_pruebas->Fecha = date('Y-m-d');
                            $ticket_pruebas->Hora = $prueba["hora"];

                            if (!$ticket_pruebas->save()) {
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "Error al agregar la prueba" . $prueba['prueba'], []];
                            }
                        }

                        #creamos PQR
                        $pqr = filter_var($request->crear_pqr, FILTER_VALIDATE_BOOLEAN);
                        
                        //Declaramos la variable con los datos del cliente realacionado al ticket que se creò en el codigo anterior
                        $cliente = $ticket->cliente;

                        if($pqr){
                            $fecha_creacion = date('Y-m-d H:i:s');

                            $anno = substr(date('y'),-2);

                            $consecutivo = PQR::select('PqrId')->where([['FechaApertura','>=', date('Y') . '-01-01 00:00:00']])->count();
                            $cun = '3563-'.$anno.'-'.str_pad(($consecutivo+1), 10, "0", STR_PAD_LEFT);

                            $pqr = new PQR;
                            $pqr->CUN = $cun;
                            $pqr->ProyectoId = $cliente->ProyectoId;
                            $pqr->ClienteId = $cliente->ClienteId;
                            $pqr->FechaApertura = $fecha_creacion;

                            #fecha estimada de cierre
                            $fecha_estimada_cierre = date('Y-m-d', strtotime(date('Y-m-d').' + 10 days'));
                            $pqr->FechaEstimada = $fecha_estimada_cierre .' ' . date('H:i:s' , strtotime($fecha_creacion));

                            $pqr->TipoEntrada = $request->canal_atencion;
                            $pqr->Solucion = $request->solucion;
                            $pqr->Hechos = $request->hechos;
                            $pqr->CorreoElectronico = $cliente->CorreoElectronico;
                            $pqr->NumeroDeCelular = $cliente->TelefonoDeContactoMovil;
                            $pqr->Prioridad = $request->prioridad;

                            $pqr->UsuarioIdAtendio = 1294;  

                            $pqr->IdentificacionCliente = $cliente->Identificacion;
                            $pqr->TipoSolicitud = 'PETICION';
                            $pqr->NombreBeneficiario = $request->nombre_pqr;
                            $pqr->DepartamentoId = $request->departamento;
                            $pqr->AvisoDePrivacidad = 'SI';
                            $pqr->AutorizaTratamientoDatos = 'SI';
                            $pqr->MunicipioId = $request->municipio;
                            $pqr->Procede = 'SI';
                            $pqr->Status = 'ABIERTO';
                            $pqr->MarcaTiempo = $fecha_creacion;

                            #fecha limite
                            $fecha_limite = date('Y-m-d', strtotime(date('Y-m-d').' + 21 days'));
                            $pqr->FechaMaxima = $fecha_limite;
                            $pqr->TipoDeEvento = '40';

                            if (!$pqr->save()) {         
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "Error al crear la PQR", []];               
                            }

                        }

                        #Creamos el mantenimiento
                        if ($escalado) {

                            if(empty($cliente->Latitud) || empty($cliente->Longitud)){
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "El cliente no tiene coordenadas!", []];
                            }

                            if(empty($cliente->Barrio)){
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "El cliente no tiene definido el Barrio", []];
                            }

                            
                            $total_mantenimientos = Mantenimiento::where('Fecha', '>', date('Y').'-01-01')->count();

                            if ($total_mantenimientos == 0) {
                                $total_mantenimientos = 1;
                            }

                            $mantenimiento = new Mantenimiento;
                            $mantenimiento->TipoMantenimiento = 'COR';
                            $mantenimiento->ProyectoId = $ticket->cliente->ProyectoId;
                            $mantenimiento->DescripcionProblema = $request->descripcion;
                            $mantenimiento->Fecha = date('Y-m-d H:i:s');
                            $mantenimiento->FechaMaxima = date('Y-m-d',strtotime(date('Y-m-d')."+ 2 day"));
                            $mantenimiento->ClienteId = $request->cliente_id;                            
                            $mantenimiento->TicketId = $ticket->TicketId;
                            $mantenimiento->CorreoCliente = $ticket->cliente->CorreoElectronico;
                            $mantenimiento->NumeroDeTicket = 'MC-'.date('y').'-'.str_pad($total_mantenimientos, 8, "0", STR_PAD_LEFT);
                            $mantenimiento->estado = 'ABIERTO';
                            $mantenimiento->DepartamentoId = $ticket->cliente->municipio->DeptId;
                            $mantenimiento->MunicipioId = $ticket->cliente->municipio_id;
                            $mantenimiento->TipoEntrada = $request->canal_atencion;
                            $mantenimiento->user_crea = Auth::user()->id;

                            $mantenimiento->Prioridad = $request->prioridad;
                            $mantenimiento->TipoFalloID = $request->tipo_falla;

                            if ($mantenimiento->save()) {
                                $clientes = new MantenimientoCliente;
                                $clientes->Identificacion = $cliente->Identificacion;
                                $clientes->ClienteId = $request->cliente_id;
                                $clientes->Mantid = $mantenimiento->MantId;

                                if(!$clientes->save()){
                                    DB::rollBack();
                                    Storage::disk('public')->deleteDirectory($directory);
                                    return ["error", "Error al agregar el cliente al mantenimiento", []];                            
                                }

                                $direcciones = new MantenimientoDireccion;
                                $direcciones->Direccion = $cliente->DireccionDeCorrespondencia;
                                $direcciones->Barrio = $cliente->Barrio;
                                $direcciones->Latitud = $cliente->Latitud;
                                $direcciones->Longitud = $cliente->Longitud;
                                $direcciones->MantId = $mantenimiento->MantId;

                                if(!$direcciones->save()){
                                    DB::rollBack();
                                    Storage::disk('public')->deleteDirectory($directory);
                                    return ["error", "Error al agregar la direccion al mantenimiento", []];                            
                                }
                                
                            }else{
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "Error al escalar a mantenimiento", []];
                            }                       


                            $novedad = new Novedad;
                            $novedad->concepto = 'Ajustes por falta de servicio';
                            $novedad->fecha_inicio = date('Y-m-d H:i:s');
                            $novedad->estado = 'PENDIENTE';
                            $novedad->unidad_medida = 'MINUTOS';
                            $novedad->ClienteId = $request->cliente_id;
                            $novedad->cobrar = false;
                            $novedad->user_id = Auth::user()->id;
                            $novedad->ticket_id = $ticket->TicketId;

                            if (!$novedad->save()) {
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ["error", "Error al agregar novedad", []];
                            }

                        }

                        if($pqr){
                            return ["success", "Ticket y Pqr creados correctamente.", ['pqr' => $pqr->CUN , 'ticket' => $ticket->TicketId]];                       
                        }else{
                            return ["success", "ticket creado satisfactoriamente", ['ticket' => $ticket->TicketId]];
                        }

                    }else{
                        DB::rollBack();
                        return ["error", "Error al crear el ticket.", []];
                    }
                });

                return response()->json(["tipo_mensaje" => $result[0], "mensaje" => $result[1], "data" => $result[2]]);

            }else{
                abort(403);
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
        if (Auth::user()->can('tickets-ver')) {
            $ticket = Ticket::findOrFail($id);
            return view('adminlte::soporte-tecnico.tickets.show', compact('ticket'));
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
        if (Auth::user()->can('tickets-editar')) {
            $ticket = Ticket::findOrFail($id);
            $ticket_medios_atencion = TicketMedioAtencion::where('Status', 'A')->get();
            $estados = EstadoTicket::get();
            $tipos_fallas = TipoFallo::where([['estado', 'ACTIVO'], ['Uso','FALLO']])->orderBy('DescipcionFallo', 'ASC')->get();
            $agentes = User::whereHas('roles',function($q){
                            $q->where('roles.name', '=', 'agente-call-center');})->orderBy('name','ASC')->get();
            $prioridades = array(array('nivel' => '1', 'descripcion' => 'Completa pérdida del servicio de internet.') , array('nivel' => '2', 'descripcion' => 'Intermitencia o Lentitud.'), array('nivel' => '3', 'descripcion' => 'Aclaración a dudas sobre la prestación del servicio.'));

            $tipos_pruebas = TicketTipoPrueba::where('estado', 'ACTIVO')->orderBy('Prueba', 'ASC')->get();

            return view('adminlte::soporte-tecnico.tickets.edit', compact('ticket', 'ticket_medios_atencion', 'estados','tipos_fallas', 'agentes','prioridades','tipos_pruebas'));
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
        if (Auth::user()->can('tickets-editar')) {

            $this->validate(request(),[
                'canal_atencion' => 'required',
                'estado' => 'required',
                'tipo_falla' => 'required',
                'fecha' => 'required',
                'prioridad' => 'required',
                'user_crea' => 'required',
                'descripcion' => 'required'
            ]);

            $result = DB::transaction(function () use($request, $id) {

                $ticket = Ticket::find($id);

                if(count($ticket->mantenimiento) > 0){
                    if ($ticket->mantenimiento->estado == 'ABIERTO' && !empty($request->fecha_cierre)) {
                        DB::rollBack();
                        return ['warning', 'No es posible actualizar hay un mantenimiento abierto!'];
                    }else{
                        if (!empty($request->fecha_cierre)) {

                            $fecha_cierre = str_replace("T", " ", $request->fecha_cierre);
                            $ticket->FechaCierre = $fecha_cierre;
                            $ticket->FechaDeSolucion = $fecha_cierre;
                            $ticket->Solucion = $request->solucion;
                            $ticket->HoraCierre = date('H:i:s', strtotime($fecha_cierre));
                            //$ticket->HoraSolucion = date('H:i:s', strtotime($fecha_cierre));

                            $ticket->mantenimiento->fecha_cierre_hora_fin = $fecha_cierre;

                            if(!$ticket->mantenimiento->save()){
                                DB::rollBack();
                                return ['error', 'Error al actualizar el mantenimiento!'];
                            }

                            #Codigo para cerrar la novedad
                            /*foreach ($ticket->novedad as $novedad) {

                                $date1 = new \DateTime($novedad->fecha_inicio);
                                $date2 = new \DateTime($fecha_cierre);
                                $diferencia = $date1->diff($date2);
                                $dias_sin_servicio = ((($diferencia->m) + ($diferencia->y * 12)) * 30) + $diferencia->d;

                                if ($dias_sin_servicio < 3) {
                                    if (!$novedad->delete()) {
                                        DB::rollBack();
                                        return ['error', 'Error al eliminar la novedad!'];
                                    }
                                }else{

                                    $novedad->fecha_fin = $fecha_cierre;

                                    if(!$novedad->save()){
                                        DB::rollBack();
                                        return ['error', 'Error actualizar la informacion de la novedad!'];
                                    }
                                }
                            }*/
                        }
                    }
                }

                $fecha = date('Y-m-d H:i:s', strtotime($request->fecha));


                $ticket->TipoDeEntrada = $request->canal_atencion;
                $ticket->FechaApertura = $fecha;
                $escalado = $request->escalar_mantenimiento;
                $ticket->EstadoDeTicket = $request->estado;
                $ticket->CodigoTipoDeFallo = $request->tipo_falla;
                $ticket->Observacion = $request->descripcion;
                $ticket->PrioridadTicket = $request->prioridad;
                $ticket->Escalado = ($escalado) ? 1 : 0;
                $ticket->FechaEscalado = ($escalado) ? $fecha : null;
                $ticket->HoraApertura = date('H:i:s', strtotime($fecha));
                $ticket->user_crea = $request->user_crea;

                if (!$ticket->save()) {
                    DB::rollBack();
                    return ['error', 'Error al actualizar el ticket!'];
                }

                return ['success', 'Ticket Actualizado correctamente!.'];

            });

            return redirect()->route('tickets.edit', $id)->with($result[0],$result[1]);

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
        if (Auth::user()->can('tickets-eliminar')) {
            $ticket = Ticket::findOrFail($id);

            if (count($ticket->novedad) > 0) {

                $novedades = $ticket->novedad;
                
                //return redirect()->route('tickets.index')->with('warning', 'entró.' . $novedad);

                foreach ($novedades as $novedad) {
                    if (count($novedad->factura_novedad) == 0){
                        $novedad->delete();
                    }else{
                        return redirect()->route('tickets.index')->with('warning', 'No se puede eliminar por que tiene novedades creadas asociadas a facturación.');
                    }
                }                
                
            }

            if (count($ticket->mantenimiento) > 0) {
                if(!$ticket->mantenimiento->delete()){
                    return redirect()->route('tickets.index')->with('error', 'Al eliminar el mantenimiento relacionado.');
                }
            }

            if (count($ticket->prueba) > 0) {
                foreach ($ticket->prueba as $prueba) {
                    if (!empty($prueba)) {
                        $prueba->delete();
                    }
                }
            }

            if($ticket->delete()){
                return redirect()->route('tickets.index')->with('success', 'Ticket eliminado.');
            }else{
                return redirect()->route('tickets.index')->with('error', 'No se pudo eliminar.');
            }
            
        }else{
            abort(403);
        }

    }

    public function exportar(Request $request){

        if (Auth::user()->can('tickets-exportar')) {
            Excel::create('tickets', function($excel) use($request) {
     
                $excel->sheet('Tickets', function($sheet) use($request) {

                    $datos = array();

                    $tickets = Ticket::                    
                     Proyecto($request->proyecto)
                    ->Departamento($request->departamento)
                    ->Municipio($request->municipio)
                    ->Estado($request->estado)                    
                    ->get();                    


                    foreach ($tickets as $key) {

                        $contador = date_diff(date_create($key->FechaApertura), date_create($key->FechaCierre));

                        $datos[] = array(
                            'PROYECTO' => (isset($key->cliente)) ? $key->cliente->proyecto->NumeroDeProyecto : '',
                            'BN' => ($key->EstadoDeTicket == 0) ? '1' : '0',
                            'MUNICIPIO' => (isset($key->cliente)) ? $key->cliente->municipio->NombreMunicipio : '',
                            'NOMBRE' => (isset($key->cliente)) ? mb_convert_case($key->cliente->NombreBeneficiario.' '. $key->cliente->Apellidos, MB_CASE_TITLE, "UTF-8") : '',
                            'CEDULA' => (isset($key->cliente)) ? $key->cliente->Identificacion : '',
                            'BARRIO' => (isset($key->cliente)) ? (empty($key->cliente->Barrio)) ? $key->cliente->NombreEdificio_o_Conjunto : $key->cliente->Barrio : '',
                            'DIRECCION' => (isset($key->cliente)) ? (empty($key->cliente->DireccionDeCorrespondencia))? $key->cliente->DireccionNomenclatura : $key->cliente->DireccionDeCorrespondencia : '' ,
                            'TELEFONO' => (isset($key->cliente)) ? $key->cliente->TelefonoDeContactoMovil : '',
                            'FECHA SOLICITUD' => $key->FechaApertura,
                            'TICKET ID' => $key->TicketId,
                            'DESCRIPCION' => (isset($key->tipo_fallo->DescipcionFallo)) ? $key->tipo_fallo->DescipcionFallo : '',
                            'FECHA CIERRE' => $key->FechaCierre,
                            'TIEMPO SIN SOLUCION' => $contador->format('%a') . ' días',
                            'ESTADO' => (isset($key->estado)) ? $key->estado->Descripcion : ''
                        );
                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('tickets.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }
}
