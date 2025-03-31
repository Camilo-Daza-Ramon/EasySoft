<?php

namespace App\Http\Controllers;

use App\Custom\ActaMantenimiento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\User;
use App\MantenimientoPreventivo;
use App\Proyecto;
use App\TipoFallo;
use App\TipoMantenimiento;
use App\Departamento;

use Carbon\Carbon;
use Excel;
use DB;
use Storage;
use Image;

class MantenimientoPreventivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('mantenimientos-preventivos-listar')) {

            $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

            $mantenimientos = MantenimientoPreventivo::
                Buscar($request->get('mantenimiento'))
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
                    }else{
                        $query->Proyecto($request->get('proyecto'))
                        ->Tipo($request->get('tipo'));
                    }

                    if(Auth::user()->hasRole('tecnico')) {
                        $query->where([['estado', 'ASIGNADO'], ['user_atiende', Auth::user()->id]]);
                    }
                })
                ->Estado($request->get('estado'))
                ->orderBy('estado', 'ASC')
                ->orderBy('Fecha', 'ASC')
                ->paginate(15);


            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto', 'Status')
            ->where(function ($query) {
                if(Auth::user()->proyectos()->count() > 0){
                    $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                }
            })->get();

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $tipos_mantenimientos = TipoMantenimiento::where('tipo', 'PREVENTIVO')->orderBy('tipo', 'ASC')->get();

            $estados = ['ABIERTO', 'ASIGNADO', 'CERRADO', 'PENDIENTE'];

            return view(
                'adminlte::soporte-tecnico.mantenimientos.preventivos.index', 
                compact(
                    'mantenimientos', 
                    'proyectos', 
                    'tipos', 
                    'estados', 
                    'departamentos',
                    'tipos_mantenimientos'
                )
            );
        } else {
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('mantenimientos-preventivos-crear')) {

            $this->validate($request, [
                'municipio' => 'required',
                'departamento' => 'required',
                'fecha_programada' => 'required',
                'tipo' => 'required'             
            ]);

            $total_mantenimientos = MantenimientoPreventivo::where('Fecha', '>', date('Y').'-01-01')->count();

            if ($total_mantenimientos == 0) {
                $total_mantenimientos = 1;
            }

            $mantenimiento = new MantenimientoPreventivo;
            $mantenimiento->user_crea = Auth::user()->id;

            $mantenimiento->NumeroDeMantenimiento = 'MP-'.date('y').'-'.str_pad($total_mantenimientos, 8, "0", STR_PAD_LEFT);

            $mantenimiento->ProyectoId = $request->proyecto;
            $mantenimiento->Municipio = $request->municipio;
            $mantenimiento->Departamento = $request->departamento;                              
            
            $mantenimiento->Fecha = date('Y-m-d');                
            $mantenimiento->fecha_programada = $request->fecha_programada;

            $mantenimiento->estado = 'ABIERTO';

            $mantenimiento->Observaciones = $request->observaciones;
            $mantenimiento->Tipo = $request->tipo;            

            if($mantenimiento->save()){
                return redirect()->route('preventivos.show', $mantenimiento->ProgMantid)->with('success', 'Mantenimiento creado correctamente.');
            }else{
                return redirect()->route('preventivos.index')->with('error', 'Error al crear el mantenimiento.');
            }
            
        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MantenimientoPreventivo  $mantenimientoPreventivo
     * @return \Illuminate\Http\Response
     */
    public function show(MantenimientoPreventivo $preventivo)
    {        
        if (Auth::user()->can('mantenimientos-preventivos-ver')) {

            $mantenimiento = $preventivo;
            $mantenimiento_tipo = "PREVENTIVO";
            $mantenimiento_id = $mantenimiento->ProgMantid;

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!in_array($mantenimiento->ProyectoId, $array)){
                    abort(403);
                }
            }            

  
            $fecha_hora_inicio = (!empty($mantenimiento->fecha_cierre_hora_inicio)) ? date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_inicio)) : null;
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

            $diganosticos = $this->getMantenimientosDiagnosticos($mantenimiento_id);
            $pruebas = $this->getMantenimientosPruebas($mantenimiento_id);

            $evidencias = $evidencias = array(
                array('archivo' => 'speedtest', 'nombre' => 'Test de Velocidad (Speedtest)'),
                array('archivo' => 'instalacion', 'nombre' => 'Evidencia de la Instalacion'),
                array('archivo' => 'mintic', 'nombre' => 'Pagina MINTIC'),
                array('archivo' => 'navegacion', 'nombre' => 'Pagina Navegación Google'),
                array('archivo' => 'ping', 'nombre' => 'Ping'),
                array('archivo' => 'youtube', 'nombre' => 'Streaming de Youtube'),
                array('archivo' => 'otro', 'nombre' => 'Otro')
            );

            return view('adminlte::soporte-tecnico.mantenimientos.preventivos.show', 
                compact(
                    'mantenimiento',
                    'indisponibilidad', 
                    'fecha_hora_inicio', 
                    'fecha_hora_fin',
                    'evidencias',
                    'diganosticos',
                    'pruebas',
                    'mantenimiento_tipo',
                    'mantenimiento_id'
                )
            );

        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MantenimientoPreventivo  $mantenimientoPreventivo
     * @return \Illuminate\Http\Response
     */
    public function edit(MantenimientoPreventivo $preventivo)
    {
        if (Auth::user()->can('mantenimientos-preventivos-editar')) {

            $mantenimiento = $preventivo;

            $fecha_hora_inicio = (!empty($mantenimiento->fecha_cierre_hora_inicio)) ? date('Y-m-d H:i:s', strtotime($mantenimiento->fecha_cierre_hora_inicio)) : null;
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

            $estados = ['ABIERTO', 'ASIGNADO', 'CERRADO', 'PENDIENTE'];

            $tipos_mantenimientos = TipoMantenimiento::where('tipo', 'PREVENTIVO')->orderBy('tipo', 'ASC')->get();
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto', 'Status')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $agentes = User::select('id', 'name')->orderBy('name', 'ASC')->get();

            $tipos_tecnologias = ['4G', '4.5G', 'Wifi', 'HFC', 'xDSL', 'FTTH'];
            $respuestas_cortas = ['SI', 'NO'];


            return view(
                'adminlte::soporte-tecnico.mantenimientos.preventivos.edit', 
                compact(
                    'mantenimiento', 
                    'indisponibilidad', 
                    'estados',
                    'tipos_mantenimientos',
                    'proyectos',
                    'departamentos',
                    'agentes',
                    'tipos_tecnologias',
                    'respuestas_cortas'
                )
            );

        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MantenimientoPreventivo  $mantenimientoPreventivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('mantenimientos-preventivos-editar')) {

            $this->validate($request, [
                'estado' => 'required',
                'tipo' => 'required',
                'departamento' => 'required',
                'municipio' => 'required',
                'agente' => 'required',
                'fecha_programada' => 'required',
            ]);

            if($request->estado == 'CERRADO'){

                $this->validate($request, [
                    'red' => 'required',
                    'fecha_cierre_hora_fin' => 'required',
                    'fecha_cierre_hora_inicio' => 'required',
                    'observaciones' => 'required',
                    'hallazgos' => 'required',
                    'procedimiento' => 'required',
                    'retorna_servicio' => 'required',
                    'servicio_activo' => 'required',
                    'tipo_tecnologia' => 'required',
                    'velocidad_descarga' => 'required',
                    'velocidad_subida' => 'required',
                    'atendido_por' => 'required',
                    'agente_cierra' => 'required',
                ]);

            }

            $result = DB::transaction(function () use($request, $id) {


                $mantenimiento = MantenimientoPreventivo::find($id);
                $mantenimiento->estado = $request->estado;
                $mantenimiento->Tipo = $request->tipo;
                $mantenimiento->ProyectoId = $request->proyecto;
                $mantenimiento->Departamento = $request->departamento;
                $mantenimiento->Municipio = $request->municipio;
                $mantenimiento->user_crea = $request->agente;                          
                $mantenimiento->fecha_programada = $request->fecha_programada;
                $mantenimiento->user_atiende = $request->atendido_por;

                if($request->estado == 'CERRADO'){
                    //DATOS PARA CERRAR
                    $mantenimiento->fecha_cierre_hora_inicio = str_replace("T", " ", $request->fecha_cierre_hora_inicio);
                    $mantenimiento->fecha_cierre_hora_fin = str_replace("T", " ", $request->fecha_cierre_hora_fin);
                    $mantenimiento->IdentificacionDeLaRed = $request->red;
                    $mantenimiento->TipoDeTecnologiaImplementada = $request->tipo_tecnologia;
                    $mantenimiento->SeRetornoServicio = $request->retorna_servicio;
                    $mantenimiento->ServicioQuedaActivo = $request->servicio_activo;
                    $mantenimiento->VelocidadDeBajada = $request->velocidad_descarga;
                    $mantenimiento->VelocidadDeSubida = $request->velocidad_subida;                
                    $mantenimiento->ObservacionesHallazgos = $request->hallazgos;
                    $mantenimiento->Procedimiento = $request->procedimiento;
                    $mantenimiento->CantidadUsuariosAfectados = 0;
                    $mantenimiento->user_cerro = $request->agente_cierra;                    
                }

                $mantenimiento->Observaciones = $request->observaciones;
                
                
                //Datos para actualizar al diligenciar clientes
                // $mantenimiento->CantClientes = 0;                
                // $mantenimiento->ClientesProgramados = null;

                if($mantenimiento->save()){

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


                    //return redirect()->route('preventivos.show',$id)->with('success', 'Mantenimiento actualizado correctamente.');
                    return ['success', 'Mantenimiento actualizado correctamente.'];

                }else{
                    //return redirect()->route('preventivos.show',$id)->with('error', 'Error al actualizar el mantenimiento.');
                    DB::rollBack();
                    return ['error', 'Error al actualizar el mantenimiento.'];
                }
            });
            
            return response()->json(['tipo_mensaje' => $result[0], 'mensaje' => $result[1]]);
            

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MantenimientoPreventivo  $mantenimientoPreventivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(MantenimientoPreventivo $preventivo)
    {
        if (Auth::user()->can('mantenimientos-preventivos-eliminar')) {

            $mantenimiento = $preventivo;

            if($mantenimiento->clientes->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Clientes relacionados.');
            }

            if($mantenimiento->archivos->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Archivos relacionados.');
            }

            if($mantenimiento->diagnosticos->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Diagnosticos relacionados.');
            }

            if($mantenimiento->pruebas->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Pruebas relacionadas.');
            }

            if($mantenimiento->direcciones->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Direcciones relacionadas.');
            }

            if($mantenimiento->equipos->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Equipos relacionados.');
            }

            if($mantenimiento->paradas_reloj->count() > 0){
                return redirect()->route('preventivos.index')->with('warning','No es posbile eliminar por que tiene Paradas de Reloj relacionados.');
            }

            if($mantenimiento->delete()){
                return redirect()->route('preventivos.index')->with('success','Mantenimiento eliminado correctamente.');
            }else{
                return redirect()->route('preventivos.index')->with('error','Error al eliminar el mantenimiento');
            }
           

        }else{
            abort(403);
        }
    }

    public function cerrar_vista($id){

        if (Auth::user()->can('mantenimientos-preventivos-cerrar')) {

            $mantenimiento = MantenimientoPreventivo::findOrFail($id);

            if($mantenimiento->estado != 'CERRADO' && $mantenimiento->fecha_programada <= date('Y-m-d')){

                $mantenimiento_tipo = "PREVENTIVO";
                $mantenimiento_id = $mantenimiento->ProgMantid;
                $diganosticos = $this->getMantenimientosDiagnosticos($mantenimiento_id);
                $pruebas = $this->getMantenimientosPruebas($mantenimiento_id);
                $link = "preventivos.cerrar_vista";

                $evidencias = array(
                    array('archivo' => 'speedtest', 'nombre' => 'Test de Velocidad (Speedtest)'),
                    array('archivo' => 'instalacion', 'nombre' => 'Evidencia de la Instalacion'),
                    array('archivo' => 'mintic', 'nombre' => 'Pagina MINTIC'),
                    array('archivo' => 'navegacion', 'nombre' => 'Pagina Navegación Google'),
                    array('archivo' => 'ping', 'nombre' => 'Ping'),
                    array('archivo' => 'youtube', 'nombre' => 'Streaming de Youtube'),
                    array('archivo' => 'firma', 'nombre' => 'Firma Cliente'),
                    array('archivo' => 'otro', 'nombre' => 'Otro')
                );

                $tipos_tecnologias = ['4G', '4.5G', 'Wifi', 'HFC', 'xDSL', 'FTTH'];
                $respuestas_cortas = ['SI', 'NO'];

                return view(
                    'adminlte::soporte-tecnico.mantenimientos.preventivos.cerrar', 
                    compact(
                        'mantenimiento',
                        'mantenimiento_tipo',
                        'mantenimiento_id',
                        'evidencias',
                        'diganosticos',
                        'pruebas',
                        'tipos_tecnologias',
                        'respuestas_cortas',
                        'link'
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
        if (Auth::user()->can('mantenimientos-preventivos-cerrar')) {

            $this->validate($request, [
                'red' => 'required',
                'fecha_cierre_hora_fin' => 'required',
                'fecha_cierre_hora_inicio' => 'required',
                'hallazgos' => 'required',
                'procedimiento' => 'required',
                'retorna_servicio' => 'required',
                'servicio_activo' => 'required',
                'tipo_tecnologia' => 'required',
                'velocidad_descarga' => 'required',
                'velocidad_subida' => 'required',
            ]);

            $result = DB::transaction(function () use($request, $id) {


                $mantenimiento = MantenimientoPreventivo::find($id);

                if($mantenimiento->estado != 'CERRADO' && $mantenimiento->fecha_programada <= date('Y-m-d')){

                    if($mantenimiento->archivos->count() == 0){
                        //return redirect()->route('preventivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Archivos.');
                        return ['warning','No es posible cerrar por que NO tiene Archivos.'];
                    }

                    if($mantenimiento->equipos->count() == 0){
                        //return redirect()->route('preventivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Equipos.');
                        return ['warning','No es posible cerrar por que NO tiene Equipos.'];
                    }

                    if($mantenimiento->diagnosticos->count() == 0){
                        //return redirect()->route('preventivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Diagnosticos.');
                        return ['warning','No es posible cerrar por que NO tiene Diagnosticos.'];
                    }

                    if($mantenimiento->pruebas->count() == 0){
                        //return redirect()->route('preventivos.cerrar_vista',$id)->with('warning','No es posible cerrar por que NO tiene Pruebas.');
                        return ['warning','No es posible cerrar por que NO tiene Pruebas.'];
                    }

                    //DATOS PARA ATENDER
                    $mantenimiento->fecha_cierre_hora_inicio = str_replace("T", " ", $request->fecha_cierre_hora_inicio);
                    $mantenimiento->fecha_cierre_hora_fin = str_replace("T", " ", $request->fecha_cierre_hora_fin);
                    $mantenimiento->IdentificacionDeLaRed = $request->red;
                    $mantenimiento->TipoDeTecnologiaImplementada = $request->tipo_tecnologia;
                    $mantenimiento->SeRetornoServicio = $request->retorna_servicio;
                    $mantenimiento->ServicioQuedaActivo = $request->servicio_activo;
                    $mantenimiento->VelocidadDeBajada = $request->velocidad_descarga;
                    $mantenimiento->VelocidadDeSubida = $request->velocidad_subida;                
                    $mantenimiento->ObservacionesHallazgos = $request->hallazgos;
                    $mantenimiento->Procedimiento = $request->procedimiento;
                    $mantenimiento->CantidadUsuariosAfectados = 0;
                    $mantenimiento->user_atiende = Auth::user()->id;
                    $mantenimiento->estado = 'PENDIENTE';

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


                        //return redirect()->route('preventivos.index')->with('success','Mantenimiento cerrado correctamente');
                        return ['success','Mantenimiento cerrado correctamente'];
                    }else{
                        //return redirect()->route('preventivos.cerrar_vista', $id)->with('error','Error al cerrar el mantenimiento.');
                        return ['error','Error al cerrar el mantenimiento.'];
                    }
                }else{
                    abort(403);
                }
            });

            return response()->json(['tipo_mensaje' => $result[0], 'mensaje' => $result[1]]);

            
        }else{
            abort(403);
        }
    }

    private function getMantenimientosDiagnosticos($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('MantenimientosDiagnosticos', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'MantenimientosDiagnosticos.DiagnosticoId')
                    ->where('MantenimientosDiagnosticos.ProgMantId', '=', $id);
            })
            ->where('Uso', 'DIAGNOSTICO')
            ->whereNull('MantenimientosDiagnosticos.DiagnosticoId')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo')
            ->get();
    }

    private function getMantenimientosPruebas($id){
        return TipoFallo::selectRaw('TB_TIPOS_FALLO.*')
            ->leftjoin('mantenimientos_pruebas', function ($join) use ($id) {
                $join->on('TB_TIPOS_FALLO.TipoFallaId', '=', 'mantenimientos_pruebas.prueba_id')
                    ->where('mantenimientos_pruebas.mantenimiento_preventivo_id', '=', $id);
            })
            ->where('Uso', 'PRUEBA')
            ->whereNull('mantenimientos_pruebas.prueba_id')
            ->orderBy('TB_TIPOS_FALLO.DescipcionFallo')
            ->get();
    }

    public function generarActa($id){
        if (!Auth::user()->can('mantenimientos-preventivos-generar-acta')) {
            abort(403);
            return;
        }

        $mantenimiento = MantenimientoPreventivo::findOrFail($id);
        
        if ($mantenimiento->estado !== 'CERRADO') {
            abort(404);
            return;
        }

        $pdf = new ActaMantenimiento('P','mm', 'A4', $mantenimiento, 'preventivo');

        $pdf->AddFont('calibri','','calibri.php');

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 25);

        $pdf->body();

        return $pdf->Output('', 'Acta Mantenimiento');
    }
}
