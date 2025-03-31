<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Custom\ActaMantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mantenimiento;
use App\Proyecto;
use App\TipoMantenimiento;
use App\TicketMedioAtencion;
use App\TipoFallo;
use App\User;
use App\Departamento;
use App\Insumo;
use App\MantenimientoCliente;
use Carbon\Carbon;
use Excel;
use DB;
use Storage;
use Image;
use Illuminate\Support\Facades\Session;

use function PHPSTORM_META\map;

class MantenimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('mantenimientos-listar')) {

            $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

            $mantenimientos = Mantenimiento::Buscar($request->get('mantenimiento'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->where(function ($query) use($request, $array) {
                    if(Auth::user()->proyectos()->count() > 0){

                        if(!empty($request->get('proyecto'))){

                            if(in_array($request->get('proyecto'), $array)){
                                $query->Proyecto($request->get('proyecto'));
                            }else{
                                $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                            }

                        }else{
                            $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                        }
                        
                        $query->where('TipoMantenimiento', 'REDT');

                    }else{
                        $query->Proyecto($request->get('proyecto'))
                        ->Tipo($request->get('tipo'));
                    }

                    if(Auth::user()->hasRole('tecnico')) {
                        $query->where([['estado', 'ASIGNADO'], ['user_atiende', Auth::user()->id]]);
                    }
                })
                ->Estado($request->get('estado'))
                ->orderBy('Estado', 'ASC')->orderBy('Fecha', 'ASC')
                ->paginate(15);

            
            $tipos = TipoMantenimiento::where('tipo', 'CORRECTIVO')->orderBy('tipo', 'ASC')->get();
            $tipos_mantenimientos = TipoMantenimiento::where('tipo', 'CORRECTIVO')->orderBy('tipo', 'ASC')->get();
            $tipos_fallas = TipoFallo::where('Uso', 'FALLO')->orderBy('DescipcionFallo', 'ASC')->get();
            $canales_atencion = TicketMedioAtencion::get();

            $prioridades = [
                '1' => 'Completa pérdida del servicio de internet al cliente.', 
                '2' => 'Servicio de internet intermitente o con lentitud', 
                '3' => 'Aclaración a dudas sobre la prestación del servicio', 
                '4' => 'Solicitud de traslado'
            ];

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto', 'Status')
            ->where(function ($query) {
                if(Auth::user()->proyectos()->count() > 0){
                    $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                }
            })->get();

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            $estados = ['ABIERTO','ASIGNADO','CERRADO', 'PENDIENTE'];

            return view( 
                'adminlte::soporte-tecnico.mantenimientos.correctivos.index',        
                compact(
                    'mantenimientos', 
                    'proyectos', 
                    'tipos', 
                    'estados', 
                    'departamentos',
                    'tipos_mantenimientos',
                    'tipos_fallas',
                    'canales_atencion',
                    'prioridades'
                )
            );

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
        if (Auth::user()->can('mantenimientos-masivos-crear')) {            

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
        if (Auth::user()->can('mantenimientos-masivos-crear')) {        

            $this->validate($request, [
                'tipo' => 'required',
                'tipo_falla' => 'required',
                'canal_atencion' => 'required',
                'prioridad' => 'required',
                'departamento' => 'required',
                'municipio' => 'required',
                'clientes_afectados' => 'required',
                'descripcion_problema' => 'required'
            ]);

            $result = DB::transaction(function () use($request) {

                $total_mantenimientos = Mantenimiento::where('Fecha', '>', date('Y').'-01-01')->count();

                if ($total_mantenimientos == 0) {
                    $total_mantenimientos = 1;
                }

                //$fecha_apertura = Carbon::parse($request->fecha_apertura);

                $mantenimiento_masivo = new Mantenimiento();
                $mantenimiento_masivo->TipoMantenimiento = $request->tipo;
                $mantenimiento_masivo->ProyectoId = $request->proyecto;
                $mantenimiento_masivo->Fecha = date('Y-m-d H:i:s');
                $mantenimiento_masivo->FechaMaxima = date("Y-m-d ", strtotime($mantenimiento_masivo->Fecha . " +48 hours"));
                $mantenimiento_masivo->NumeroDeTicket = 'MC-'.date('y').'-'.str_pad($total_mantenimientos, 8, "0", STR_PAD_LEFT);
                $mantenimiento_masivo->estado = 'ABIERTO';
                $mantenimiento_masivo->DepartamentoId = $request->departamento;
                $mantenimiento_masivo->MunicipioId = $request->municipio;
                $mantenimiento_masivo->TipoEntrada = $request->canal_atencion;
                $mantenimiento_masivo->DescripcionProblema = $request->descripcion_problema;
                $mantenimiento_masivo->user_crea = Auth::user()->id;
                $mantenimiento_masivo->Prioridad = $request->prioridad;
                $mantenimiento_masivo->TipoFalloID = $request->tipo_falla;

                if ($mantenimiento_masivo->save()) {

                    $cedulas_especificas = explode(',', $request->clientes_afectados);

                    $clientes = Cliente::select('ClienteId','Identificacion')->where([
                        ['municipio_id', $request->municipio],
                        ['Status', 'ACTIVO'],
                        ['EstadoDelServicio', 'Activo']
                    ])->whereIn('Identificacion', $cedulas_especificas)->get();

                    if($clientes->count() > 0){
                        foreach ($clientes as $cliente) {                      
                            $mantenimiento_cliente = new MantenimientoCliente();
                            $mantenimiento_cliente->Identificacion = $cliente->Identificacion;
                            $mantenimiento_cliente->ClienteId = $cliente->ClienteId;
                            $mantenimiento_cliente->Mantid = $mantenimiento_masivo->MantId;
            
                            if(!$mantenimiento_cliente->save()){
                                DB::rollBack();
                                return ['error', 'Error al crear el mantenimiento masivo'];
                            }
                        }
                    }else{
                        DB::rollBack();
                        return ['error', 'Los clientes suministrados no pertenecen al municipio del mantenimiento.'];
                    }                    
        
                    return ['success', 'Mantenimiento masivo creado correctamente'];
                    
                }else{
                    DB::rollBack();
                    return ['error', 'Error al crear el mantenimiento masivo'];
                }            
            });

            return redirect()->route('correctivos.index')->with($result[0], $result[1]);


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

        if (Auth::user()->can('mantenimientos-ver')) {
            
            $mantenimiento_tipo = "CORRECTIVO";
            $mantenimiento_id = $id;

            $mantenimiento = Mantenimiento::findOrFail($id);

            if(Auth::user()->hasRole('tecnico') && $mantenimiento->user_atiende != Auth::user()->id) {

                abort(403);

            }elseif(Auth::user()->hasRole('tecnico') && $mantenimiento->estado != "ASIGNADO"){
                abort(403);
            }

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!in_array($mantenimiento->ProyectoId, $array)){
                    abort(403);
                }

                if($mantenimiento->TipoMantenimiento !== 'REDT'){
                    abort(403);
                }

            }

            

  
            $fecha_hora_inicio = (!empty($mantenimiento->Fecha)) ? date('Y-m-d H:i:s', strtotime($mantenimiento->Fecha)) : null;
            $fecha_hora_fin = (!empty($mantenimiento->fecha_cierre_hora_fin)) ? date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin)) : null;

            $indisponibilidad = [
                'dias' => '',
                'horas' => '',
                'minutos' => '',
                'compensar' => false
            ];

            if (!empty($fecha_hora_inicio) && !empty($fecha_hora_fin)) {
                $contador = date_diff(date_create($fecha_hora_inicio), date_create($fecha_hora_fin));

                $indisponibilidad['dias'] = $contador->days; //$contador->format('%a');
                $indisponibilidad['minutos'] = ($contador->days * 1440) + ($contador->h * 60) + $contador->i;
                $indisponibilidad['horas'] = $indisponibilidad['minutos'] / 60; //$contador->format('%h') + ($contador->format('%i')/60);
                //$indisponibilidad['minutos'] = ($contador->format('%h') * 60) + $contador->format('%i');

                $tiempo_mes = 43200;

                $porcentaje = floatval(number_format((($indisponibilidad['minutos'] * 100) / $tiempo_mes), 3, ".", ""));

                if ((100 - $porcentaje) < 99.80) {
                    
                    $indisponibilidad['compensar'] = true;
                }
            }


            $cedulas_array = MantenimientoCliente::select('Clientes.Identificacion')
                ->join('Clientes', 'MantenimientoProgramacionClientes.ClienteId', 'Clientes.ClienteId')
                ->leftjoin('novedades', function ($join) use ($id) {
                    $join->on('MantenimientoProgramacionClientes.ClienteId', '=', 'novedades.ClienteId')
                        ->where('novedades.mantenimiento_id', '=', $id);
                })
                ->where('MantenimientoProgramacionClientes.MantId', $id)
                ->whereNull('novedades.ClienteId')
                ->get();


            $cedulas = "";

            foreach ($cedulas_array as $cliente) {
                $cedulas .= $cliente->Identificacion . ",";
            }

            $diganosticos = $this->getMantenimientosDiagnosticos($id);
            $evidencias = $this->getMantenimientosEvidencias($mantenimiento);
            $pruebas = $this->getMantenimientosPruebas($id);
            $soluciones = $this->getMantenimientosSoluciones($id);
            $fallos = $this->getMantenimientosFallos($id);
            $insumos_materiales = $this->getMantenimientosInsumosMateriales();

            $unidades_medidas = ['UNIDAD', 'METROS', 'CENTIMETROS'];

            return view(
                'adminlte::soporte-tecnico.mantenimientos.correctivos.show',
                compact(
                    'mantenimiento', 
                    'indisponibilidad', 
                    'fecha_hora_inicio', 
                    'fecha_hora_fin', 
                    'cedulas',
                    'evidencias',
                    'diganosticos',
                    'pruebas',
                    'soluciones',
                    'fallos',
                    'insumos_materiales',
                    'mantenimiento_tipo',
                    'mantenimiento_id',
                    'unidades_medidas'
                ));
        } else {
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
        if (Auth::user()->can('mantenimientos-editar')) {

            $mantenimiento = Mantenimiento::findOrFail($id);
            $mantenimiento_tipo = "CORRECTIVO";
            $mantenimiento_id = $id;

            $canales_atencion = TicketMedioAtencion::get();
            $estados = ['ABIERTO','ASIGNADO','CERRADO', 'PENDIENTE'];

            $tipos_mantenimientos = TipoMantenimiento::where('tipo', 'CORRECTIVO')->orderBy('tipo', 'ASC')->get();

            $tipos_fallas = TipoFallo::where('Uso', 'FALLO')->get();

            $diganosticos = $this->getMantenimientosDiagnosticos($id);

            $pruebas = $this->getMantenimientosPruebas($id);

            $soluciones = $this->getMantenimientosSoluciones($id);
            
            $fallos = $this->getMantenimientosFallos($id);

            $agentes = User::select('id', 'name')
                ->orderBy('name', 'ASC')->get();

            $prioridades = ['1' => 'Completa pérdida del servicio de internet al cliente.', '2' => 'Servicio de internet intermitente o con lentitud', '3' => 'Aclaración a dudas sobre la prestación del servicio', '4' => 'Solicitud de traslado'];

            $tipos_tecnologias = ['4G', '4.5G', 'Wifi', 'HFC', 'xDSL', 'FTTH'];

            $respuestas_cortas = ['SI', 'NO'];

            $unidades_medidas = ['UNIDAD', 'METROS', 'CENTIMETROS'];

            $parentezcos = ['TITULAR', 'FAMILIAR', 'OTRO'];

            $departamentos = Departamento::select('Departamentos.DeptId', 'Departamentos.NombreDelDepartamento')
                ->join('Municipios', 'Departamentos.DeptId', 'Municipios.DeptId')
                ->join('proyectos_municipios', 'Municipios.MunicipioId', 'proyectos_municipios.municipio_id')
                ->join('Mantenimientos', 'proyectos_municipios.proyecto_id', 'Mantenimientos.ProyectoId')
                ->groupBy(['Departamentos.DeptId', 'Departamentos.NombreDelDepartamento'])
                ->get();

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            $evidencias = $this->getMantenimientosEvidencias($mantenimiento);

            $insumos_materiales = $this->getMantenimientosInsumosMateriales();

            return view(
                'adminlte::soporte-tecnico.mantenimientos.correctivos.edit',
                compact(
                    'mantenimiento',
                    'canales_atencion',
                    'estados',
                    'tipos_mantenimientos',
                    'tipos_fallas',
                    'agentes',
                    'prioridades',
                    'tipos_tecnologias',
                    'respuestas_cortas',
                    'departamentos',
                    'proyectos',
                    'evidencias',
                    'diganosticos',
                    'pruebas',
                    'soluciones',
                    'fallos',
                    'insumos_materiales',
                    'mantenimiento_id',
                    'mantenimiento_tipo',
                    'unidades_medidas',
                    'parentezcos'
                )
            );
        } else {
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
        if(Auth::user()->can('mantenimientos-editar')){
            $this->validate($request, [
                'departamento' => 'required',
                'municipio' => 'required',
                'canal_atencion' => 'required',
                'tipo_mantenimiento' => 'required',                
                'creado_por' => 'required',
                'fecha_apertura' => 'required',
                'fecha_limite' => 'required|after_or_equal:fecha_apertura',
                'tipo_falla' => 'required',
                'prioridad' => 'required',                
                'descripcion_problema' => 'required'
            ]);
            
            if($request->estado == 'CERRADO'){
                $this->validate($request, [
                    'fecha_hora_inicio_cierre' => 'required',
                    'fecha_hora_fin_cierre' => 'required',
                    'tipo_tecnologia' => 'required',
                    'red' => 'required',
                    'retorna_servicio' => 'required',
                    'servicio_activo' => 'required',
                    'velocidad_subida' => 'required',
                    'velocidad_descarga' => 'required',
                    'solucion' => 'required',
                    'procedimiento' => 'required',
                    'observaciones' => 'required',
                    'observaciones_cierre' => 'required',
                    'atendido_por' => 'required',
                    'cerrado_por' => 'required',
                ]);
            }

            $result = DB::transaction(function () use($request, $id) {
    
                $fecha_apertura = Carbon::parse($request->fecha_apertura);
                $fecha_limite = Carbon::parse($request->fecha_limite);
                $fecha_cierre = Carbon::parse($request->fecha_hora_inicio_cierre);

                $mantenimiento = Mantenimiento::find($id);

                $estado_antes = $mantenimiento->estado;
                $estado_despues = $request->estado;

                $mantenimiento->ProyectoId = $request->proyecto;
                $mantenimiento->DepartamentoId = $request->departamento;
                $mantenimiento->MunicipioId = $request->municipio;
                $mantenimiento->TipoEntrada = $request->canal_atencion;
                $mantenimiento->TipoMantenimiento = $request->tipo_mantenimiento;
                $mantenimiento->user_crea = $request->creado_por;
                $mantenimiento->user_atiende = $request->atendido_por;
                $mantenimiento->Fecha = $fecha_apertura->toDateTimeString();
                $mantenimiento->FechaMaxima = $fecha_limite->toDateTimeString();
                $mantenimiento->TipoFalloID = $request->tipo_falla;
                $mantenimiento->Prioridad = $request->prioridad;
                $mantenimiento->DescripcionProblema = $request->descripcion_problema;
                $mantenimiento->estado = $request->estado;                

                if($request->estado == 'CERRADO' || $request->estado == 'PENDIENTE'){
                    $mantenimiento->fecha_cierre_hora_inicio = str_replace("T", " ", $request->fecha_hora_inicio_cierre);
                    $mantenimiento->fecha_cierre_hora_fin = str_replace("T", " ", $request->fecha_hora_fin_cierre);
                    
                    $mantenimiento->TipoDeTecnologiaImplementada = $request->tipo_tecnologia;
                    $mantenimiento->Red = $request->red;
                    $mantenimiento->SeRetornoServicio = $request->retorna_servicio;
                    $mantenimiento->ServicioQuedaActivo = $request->servicio_activo;
                    $mantenimiento->VelocidadDeSubida = $request->velocidad_subida;
                    $mantenimiento->VelocidadDeBajada = $request->velocidad_descarga;
                    $mantenimiento->Solucion = $request->solucion;
                    $mantenimiento->Procedimiento = $request->procedimiento;
                    $mantenimiento->Observaciones = $request->observaciones;
                    $mantenimiento->ObservacionDeCierre = $request->observaciones_cierre;
                    $mantenimiento->user_atiende = $request->atendido_por;
                    $mantenimiento->user_cerro = $request->cerrado_por;
                }

                if(!empty($mantenimiento->ClienteId)){
                
                    $mantenimiento->parentezco = ($request->parentezco != 'TITULAR')? strtoupper($request->recibe_otro) : $request->parentezco;
                    $mantenimiento->nombre = $request->recibe_nombre;
                    $mantenimiento->cedula = $request->recibe_cedula;

                    if(!empty($request->firma)){

                        if(!empty($mantenimiento->firma)){
                            Storage::delete($mantenimiento->firma);                            
                        }

                        $directory = 'mantenimientos/correctivos/'.$mantenimiento->MantId;
                        $extension = 'jpg';
                        $nombre = "firma";

                        $tamaño = 800;

                        $file = $request->firma;

                        $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode($extension)->__toString();

                        $ruta = $directory.'/'.$nombre.'.'.$extension;

                        Storage::put($ruta, $file);

                        $existe = Storage::exists($ruta);                    

                        if ($existe) {
                            $mantenimiento->firma = $ruta;
                        }else{
                            DB::rollBack();
                            Storage::deleteDirectory($directory);
                            return ['error', 'Error al subir la firma del cliente'];
                        }
                    }
                }

                if ($mantenimiento->save()) {

                    if($estado_antes == 'PENDIENTE' && $estado_despues == 'CERRADO'){
                        if(!empty($mantenimiento->TicketId)){
                            $ticket = $mantenimiento->ticket;

                            $ticket->FechaCierre = $mantenimiento->fecha_cierre_hora_fin;
                            $ticket->FechaDeSolucion = $mantenimiento->fecha_cierre_hora_fin;
                            $ticket->Solucion = $mantenimiento->Solucion;
                            //$ticket->HoraCierre = date('H:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin));
                            $ticket->EstadoDeTicket = 0;
                            if($ticket->save()){

                                if($ticket->novedad->count() > 0){

                                    foreach ($ticket->novedad as $novedad) {

                                        $date1 = new \DateTime($novedad->fecha_inicio);
                                        $date2 = new \DateTime($mantenimiento->fecha_cierre_hora_fin);
                                        $diferencia = $date1->diff($date2);
                                        $dias_sin_servicio = ((($diferencia->m) + ($diferencia->y * 12)) * 30) + $diferencia->d;
                                        $minutos_sin_servicio = ($diferencia->days * 1440) + ($diferencia->h * 60) + $diferencia->i;
                                       
                                        if ($minutos_sin_servicio < 420) {
                                            if (!$novedad->delete()) {
                                                DB::rollBack();
                                                return ['error', 'Error al eliminar la novedad.'];
                                            }
                                        }else{
                                            $novedad->fecha_fin = $mantenimiento->fecha_cierre_hora_fin;

                                            if(!$novedad->save()){
                                                DB::rollBack();
                                                return ['error', 'Error al actualizar la novedad.'];
                                            }
                                        }
                                    }
                                }

                            }else{
                                DB::rollBack();
                                return ['error', 'Error al cerrar el ticket'];
                            }

                        }
                    }

                    if(!empty($request->firma_usuario)){

                        $tecnico = $mantenimiento->usuario_atiende;

                        if(!empty($tecnico->firma)){
                            Storage::delete($tecnico->firma);                            
                        }
    
                        $file = $request->firma_usuario;
                        $directory = 'usuarios/'. $tecnico->id;
                        $nombre = "firma";
                        $extension = 'jpg';
                        $tamaño = 800;
    
                        $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode($extension)->__toString();
    
                        //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                        $ruta = $directory.'/'.$nombre.'.'.$extension;
    
                        //Indicamos que queremos guardar un nuevo archivo en el disco local        
                        Storage::disk('public')->put($ruta, $file);
    
                        $existe = Storage::disk('public')->exists($ruta);
    
                        if ($existe) {
    
                            $tecnico->firma = $ruta;
    
                            if(!$tecnico->save()){
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ['error', 'Error al guardar la firma del tecnico'];
                            }
    
                        }else{
                            DB::rollBack();
                            Storage::disk('public')->deleteDirectory($directory);
                            return ['error', 'Error al subir la firma del tecnico'];
                        }
    
                    }
                    
                    return ['success', 'Mantenimiento actualizado correctamente.'];
                }else{
                    DB::rollBack();
                    return ['error', 'Error al actualizar el mantenimiento.'];
                }
            });

            //return redirect()->route('correctivos.index')->with($result[0], $result[1]);
            return response()->json(['tipo_mensaje' => $result[0], 'mensaje' => $result[1]]);

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

    public function exportar(Request $request)
    {

        if (Auth::user()->can('mantenimientos-exportar')) {

            $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

            $mantenimientos = Mantenimiento::Buscar($request->get('mantenimiento'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->where(function ($query) use($request, $array) {
                    if(Auth::user()->proyectos()->count() > 0){

                        if(!empty($request->get('proyecto'))){

                            if(in_array($request->get('proyecto'), $array)){
                                $query->Proyecto($request->get('proyecto'));
                            }else{
                                $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                            }

                        }else{
                            $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                        }
                        
                        $query->where('TipoMantenimiento', 'REDT');

                    }else{
                        $query->Proyecto($request->get('proyecto'))
                        ->Tipo($request->get('tipo'));
                    }
                })
                ->Estado($request->get('estado'))
                ->orderBy('Estado', 'ASC')->orderBy('Fecha', 'ASC')
                ->get();

                
            if (count($mantenimientos) == 0) {
                return response()->json([$mantenimientos, 'No hay datos para el filtro enviado.']);                
            }

            Excel::create('mantenimientos', function ($excel) use ($mantenimientos) {
                $excel->sheet('Mantenimientos', function ($sheet) use ($mantenimientos) {

                    $datos = array();

                    foreach ($mantenimientos as $mantenimiento) {

                        $fecha_hora_inicio = date('Y-m-d H:i:s', strtotime($mantenimiento->Fecha));

                        $fecha_hora_fin = (!empty($mantenimiento->fecha_cierre_hora_fin))? date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_fin)) : null;

                        $contador = date_diff(date_create($fecha_hora_inicio), date_create($fecha_hora_fin));

                        $datos[] = array(
                            'ID' => $mantenimiento->MantId,
                            //'# MANTENIMIENTO' => $mantenimiento->TicketNumero,
                            '# MANTENIMIENTO' => $mantenimiento->NumeroDeTicket,
                            'TICKET ASOCIADO' => $mantenimiento->TicketId,
                            'CEDULA CLIENTE' => (isset($mantenimiento->cliente)) ? $mantenimiento->cliente->Identificacion : '',
                            'PROYECTO' => (isset($mantenimiento->proyectos)) ? $mantenimiento->proyectos->NumeroDeProyecto : $mantenimiento->ProyectoId,
                            'DEPARTAMENTO' => $mantenimiento->municipio->NombreDepartamento,
                            'MUNICIPIO' => $mantenimiento->municipio->NombreMunicipio,
                            'DIRECCION' => $mantenimiento->Direccion,
                            'BARRIO' => $mantenimiento->Barrrio,
                            'COORDENADAS' => $mantenimiento->Latitud . "," . $mantenimiento->Longitud,
                            'TIPO' => $mantenimiento->tipo_mantenimiento->Descripcion,
                            'AGENTE CREA MANTENIMIENTO' => (!empty($mantenimiento->user_crea))? $mantenimiento->usuario_crea->name : null,
                            'AGENTE ATIENDE MANTENIMIENTO' => (!empty($mantenimiento->user_atiende))? $mantenimiento->usuario_atiende->name: null,
                            'AGENTE CIERRA MANTENIMIENTO' => (!empty($mantenimiento->user_cerro))? $mantenimiento->usuario_cierra->name: null,
                            'FECHA CREACION' => $mantenimiento->Fecha,
                            'FECHA CIERRE HORA INICIO' => $mantenimiento->fecha_cierre_hora_inicio,
                            'FECHA CIERRE HORA FIN ' => $mantenimiento->fecha_cierre_hora_fin,
                            'DESCRIPCION PROBLEMA' => $mantenimiento->DescripcionProblema,
                            'OBSERVACIONES' => $mantenimiento->Observaciones,
                            'SOLUCION' => $mantenimiento->Solucion,                            
                            'OBSERVACIONES CIERRE' => $mantenimiento->ObservacionDeCierre,
                            'PROCEDIMIENTO' => $mantenimiento->Procedimiento,
                            'TIPO FALLA' => (isset($mantenimiento->tipo_fallo)) ? $mantenimiento->tipo_fallo->DescipcionFallo : '',
                            'TIPO SOLUCION' => $mantenimiento->TipoSolucionId,
                            'TIPO CIERRE' => $mantenimiento->TipoCierreId,
                            'PRIORIDAD' => $mantenimiento->Prioridad,
                            'TIPO ENTRADA' => (isset($mantenimiento->medio_atencion)) ? $mantenimiento->medio_atencion->Descripcion : '',
                            'ESTADO' => $mantenimiento->Estado,
                            'RED' => $mantenimiento->Red,
                            'TIPO TECNOLOGIA' => $mantenimiento->TipoDeTecnologiaImplementada,
                            'CANTIDAD CLIENTES AFECTADOS' => $mantenimiento->CantidadUsuariosAfectados,
                            'SE RETORNA EL SERVICIO' => $mantenimiento->SeRetornoServicio,
                            'SERVICIO ACTIVO?' => $mantenimiento->ServicioQuedaActivo,
                            'TIPOLOGIA IMPLEMENTADA' => $mantenimiento->TipologiaImplementada,
                            'IDENTIFICACION DE LA RED' => $mantenimiento->IdentificacionDeLaRed,
                            'VEL. SUBIDA' => $mantenimiento->VelocidadDeSubida,
                            'VEL. BAJADA' => $mantenimiento->VelocidadDeBajada,
                            'TIEMPO SIN SOLUCION (Minutos)' => ($contador->days * 1440) + ($contador->h * 60) + $contador->i
                        );
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);
                });
            })->export('xlsx');

        } else {
            abort(403);
        }
    }

    public function cerrar_vista($id){

        if (Auth::user()->can('mantenimientos-cerrar')) {

            $mantenimiento = Mantenimiento::findOrFail($id);

            if($mantenimiento->estado != 'CERRADO'){

                $mantenimiento_tipo = "CORRECTIVO";
                $mantenimiento_id = $mantenimiento->MantId;
                $diganosticos = $this->getMantenimientosDiagnosticos($mantenimiento_id);
                $pruebas = $this->getMantenimientosPruebas($mantenimiento_id);
                $link = "correctivos.cerrar_vista";

                $soluciones = $this->getMantenimientosSoluciones($id);

                $evidencias = $this->getMantenimientosEvidencias($mantenimiento);

                $insumos_materiales = $this->getMantenimientosInsumosMateriales();

                $indisponibilidad = [
                    'dias' => '',
                    'horas' => '',
                    'minutos' => '',
                    'compensar' => false
                ];

                $tipos_tecnologias = ['4G', '4.5G', 'Wifi', 'HFC', 'xDSL', 'FTTH'];
                $respuestas_cortas = ['SI', 'NO'];
                $unidades_medidas = ['UNIDAD', 'METROS', 'CENTIMETROS'];


                return view(
                    'adminlte::soporte-tecnico.mantenimientos.correctivos.cerrar', 
                    compact(
                        'mantenimiento',
                        'mantenimiento_tipo',
                        'mantenimiento_id',
                        'evidencias',
                        'diganosticos',
                        'pruebas',
                        'tipos_tecnologias',
                        'respuestas_cortas',
                        'link',
                        'unidades_medidas',
                        'insumos_materiales',
                        'soluciones',
                        'indisponibilidad'
                    )
                );

            }else{
                abort(403);
            }

        }else{
            abort(403);
        }
    }

    /**
     * cerrar the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MantenimientoPreventivo  $preventivo
     * @return \Illuminate\Http\Response
     */
    public function cerrar(Request $request, $id)
    {
        
        if (Auth::user()->can('mantenimientos-cerrar')) {

            $this->validate($request, [
                'fecha_hora_inicio_cierre' => 'required',
                'fecha_hora_fin_cierre' => 'required',
                'tipo_tecnologia' => 'required',
                'red' => 'required',
                'retorna_servicio' => 'required',
                'servicio_activo' => 'required',
                'velocidad_subida' => 'required',
                'velocidad_descarga' => 'required',
                'solucion' => 'required',
                'procedimiento' => 'required',
                'observaciones' => 'required',
                'observaciones_cierre' => 'required',
            ]);

            $result = DB::transaction(function () use($request, $id) {

                $mantenimiento = Mantenimiento::find($id);

                if($mantenimiento->estado != 'CERRADO'){

                    if($mantenimiento->archivos->count() == 0){
                        //return redirect()->route('correctivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Archivos.');
                        return ['warning', 'No es posible cerrar por que NO tiene Archivos.'];
                    }

                    /*if($mantenimiento->equipos->count() == 0){
                        return redirect()->route('correctivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Equipos.');
                    }*/

                    if($mantenimiento->soluciones->count() == 0){
                        //return redirect()->route('correctivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Soluciones especificas.');
                        return ['warning', 'No es posible cerrar por que NO tiene Soluciones especificas.'];
                    }

                    if($mantenimiento->diagnosticos->count() == 0){
                        //return redirect()->route('correctivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Diagnosticos.');
                        return ['warning', 'No es posible cerrar por que NO tiene Diagnosticos.'];
                    }

                    if($mantenimiento->pruebas->count() == 0){
                        //return redirect()->route('correctivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Pruebas.');
                        return ['warning', 'No es posible cerrar por que NO tiene Pruebas.'];
                    }

                    //DATOS PARA CERRAR
                    $mantenimiento->fecha_cierre_hora_inicio = str_replace("T", " ", $request->fecha_hora_inicio_cierre);
                    $mantenimiento->fecha_cierre_hora_fin = str_replace("T", " ", $request->fecha_hora_fin_cierre);
                    $mantenimiento->Red = $request->red;
                    $mantenimiento->TipoDeTecnologiaImplementada = $request->tipo_tecnologia;
                    $mantenimiento->SeRetornoServicio = $request->retorna_servicio;
                    $mantenimiento->ServicioQuedaActivo = $request->servicio_activo;
                    $mantenimiento->VelocidadDeBajada = $request->velocidad_descarga;
                    $mantenimiento->VelocidadDeSubida = $request->velocidad_subida;                
                    $mantenimiento->Procedimiento = $request->procedimiento;
                    $mantenimiento->user_atiende = Auth::user()->id;
                    $mantenimiento->estado = 'PENDIENTE';
                    $mantenimiento->Solucion = $request->solucion;
                    $mantenimiento->Observaciones = $request->observaciones;
                    $mantenimiento->ObservacionDeCierre = $request->observaciones_cierre;

                    if(!empty($request->firma)){
                        $mantenimiento->parentezco = ($request->parentezco != 'TITULAR')? strtoupper($request->recibe_otro) : $request->parentezco;
                        $mantenimiento->nombre = $request->recibe_nombre;
                        $mantenimiento->cedula = $request->recibe_cedula;

                        $directory = 'mantenimientos/correctivos/'.$mantenimiento->MantId;
                        $extension = 'jpg';
                        $nombre = "firma";

                        $tamaño = 800;

                        $file = $request->firma;

                        $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode($extension)->__toString();

                        $ruta = $directory.'/'.$nombre.'.'.$extension;

                        Storage::put($ruta, $file);

                        $existe = Storage::exists($ruta);                    

                        if ($existe) {
                            $mantenimiento->firma = $ruta;
                        }else{
                            DB::rollBack();
                            Storage::deleteDirectory($directory);
                            return ['error', 'Error al subir la firma del cliente'];
                        }
                    }
                    

                    if($mantenimiento->save()){

                        if(!empty($request->firma_usuario)){

                            $tecnico = User::find(Auth::user()->id);
        
                            $file = $request->firma_usuario;
                            $directory = 'usuarios/'. $tecnico->id;
                            $nombre = "firma";
                            $extension = 'jpg';
                            $tamaño = 800;
        
                            $file = Image::make($file)->resize($tamaño,null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode($extension)->__toString();
        
                            //Obtenemos el campo file definido en el formulario y se lo enviamos a la funcion 
                            $ruta = $directory.'/'.$nombre.'.'.$extension;
        
                            //Indicamos que queremos guardar un nuevo archivo en el disco local        
                            Storage::disk('public')->put($ruta, $file);
        
                            $existe = Storage::disk('public')->exists($ruta);
        
                            if ($existe) {
        
                                $tecnico->firma = $ruta;
        
                                if(!$tecnico->save()){
                                    DB::rollBack();
                                    Storage::disk('public')->deleteDirectory($directory);
                                    return ['error', 'Error al guardar la firma del tecnico'];
                                }
        
                            }else{
                                DB::rollBack();
                                Storage::disk('public')->deleteDirectory($directory);
                                return ['error', 'Error al subir la firma del tecnico'];
                            }
        
                        }

                        //return redirect()->route('correctivos.index')->with('success','Mantenimiento cerrado correctamente');
                        return ['success', 'Mantenimiento cerrado correctamente'];

                    }else{
                        //return redirect()->route('correctivos.cerrar_vista', $id)->with('error','Error al cerrar el mantenimiento.');
                        DB::rollBack();
                        Storage::deleteDirectory($directory);
                        return ['error', 'Error al cerrar el mantenimiento.'];
                    }
                }else{
                    abort(403);
                }
            });

            return response()->json(['tipo_mensaje' => $result[0], 'mensaje' => $result[1]]);
            
        }else{

        }
    }



    private function getMantenimientosDiagnosticos($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('MantenimientosDiagnosticos', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'MantenimientosDiagnosticos.DiagnosticoId')
                    ->where('MantenimientosDiagnosticos.MantId', '=', $id);
            })
            ->where('Uso', 'DIAGNOSTICO')
            ->whereNull('MantenimientosDiagnosticos.DiagnosticoId')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo')
            ->get();
    }

    private function getMantenimientosEvidencias($mantenimiento){
        $evidencias = array(
            array('archivo' => 'speedtest', 'nombre' => 'Test de Velocidad (Speedtest)'),
            array('archivo' => 'instalacion', 'nombre' => 'Evidencia de la Instalacion'),
            array('archivo' => 'mintic', 'nombre' => 'Pagina MINTIC'),
            array('archivo' => 'navegacion', 'nombre' => 'Pagina Navegación Google'),
            array('archivo' => 'ping', 'nombre' => 'Ping'),
            array('archivo' => 'youtube', 'nombre' => 'Streaming de Youtube'),
            array('archivo' => 'otro', 'nombre' => 'Otro')
        );

        $mantenimiento_evidencias = $mantenimiento->archivos->map(function ($element) {
            return strtolower($element->nombre);
        });
        
        $evidencias = array_filter($evidencias, function ($value) use($mantenimiento_evidencias) {
            return !in_array($value['archivo'], $mantenimiento_evidencias->toArray()); 
        });

        return $evidencias;
    }

    private function getMantenimientosPruebas($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('mantenimientos_pruebas', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'mantenimientos_pruebas.prueba_id')
                    ->where('mantenimientos_pruebas.mantenimiento_id', '=', $id);
            })
            ->where('Uso', 'PRUEBA')
            ->whereNull('mantenimientos_pruebas.prueba_id')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo')
            ->get();
    }

    private function getMantenimientosSoluciones($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('mantenimientos_soluciones', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'mantenimientos_soluciones.solucion_id')
                    ->where('mantenimientos_soluciones.mantenimiento_id', '=', $id);
            })
            ->where('Uso', 'SOLUCION')
            ->whereNull('mantenimientos_soluciones.solucion_id')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo')
            ->get();
    }

    private function getMantenimientosFallos($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('MantenimientosFallas', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'MantenimientosFallas.TipoFallaId')
                    ->where('MantenimientosFallas.MantId', '=', $id);
            })
            ->where('Uso', 'FALLO')
            ->whereNull('MantenimientosFallas.TipoFallaId')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo', 'ASC')
            ->get();
    }

    private function getMantenimientosInsumosMateriales(){
        return Insumo::where('InsumoTipo', '=', 'MATERIAL')
            ->select(['InsumoId', 'Descripcion'])
            ->orderBy('Descripcion')->get();
    }

    public function generarActa($id){
        if (!Auth::user()->can('mantenimientos-generar-acta')) {
            abort(403);
            return;
        }

        $mantenimiento = Mantenimiento::findOrFail($id);
        
        if ($mantenimiento->estado !== 'CERRADO') {
            abort(404);
            return;
        }

        $pdf = new ActaMantenimiento('P','mm', 'A4', $mantenimiento, 'correctivo');

        $pdf->AddFont('calibri','','calibri.php');

        $pdf->AliasNbPages();
        
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 1);

        $pdf->body();

        return $pdf->Output('', 'Acta Mantenimiento');
    }
}
