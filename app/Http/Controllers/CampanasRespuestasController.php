<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Solicitud;
use App\AcuerdoPago;
use App\Facturacion;
use App\CampanaCampos;
use App\MotivoAtencion;
use App\AtencionCliente;
use App\CampanaClientes;
use App\CamposVisualizar;
use App\CampanaRespuestas;
use Illuminate\Http\Request;
use App\CampanaObservaciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Storage;
use Image;

 
class CampanasRespuestasController extends Controller
{
    private function respuestaCampo($campo_solicitud, $cliente, $request)
    {
        $respuesta_camp = new CampanaRespuestas();
        $respuesta_camp->campo_id = $campo_solicitud->id;
        $respuesta_camp->campana_cliente_id = $cliente;
        $respuesta_camp->usuario_id = auth()->user()->id;               
        $respuesta_camp->respuesta = $request;
               
        $respuesta = $respuesta_camp->save();
        return $respuesta;
         
    }

    private function respuestaObservacion($request, $cliente){
        $observacion = new CampanaObservaciones();
        $observacion->observacion = $request->observacion; 
        $observacion->campana_cliente_id = $cliente;
        $observacion->usuario_id = auth()->user()->id;

        $respuesta = $observacion->save();
        return $respuesta;
    }
  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id , $cliente_id)
    {
        if (Auth::user()->can('campañas-respuestas-crear')){
            
            //$campaña = Campana::findOrFail($id); 
            $cliente = CampanaClientes::findOrFail($cliente_id);

            if($cliente->respuestas->count() > 0){
                return redirect()->route('campanas.show', $id)->with('warning', 'Este cliente no tiene preguntas pendientes por responder.');
            }elseif($cliente->campana->estado == 'FINALIZADA'){
                return redirect()->route('campanas.show', $id)->with('warning', 'La campaña ya finalizó');

            }else{

                if(!empty($cliente->fecha_hora_rellamar)){
                    if($cliente->fecha_hora_rellamar > date('Y-m-d H:i:s')){
                        abort(403);
                    }
                } 
                
                $campaña = $cliente->campana;
                $acuerdos_activos = AcuerdoPago::where([['cliente_id',$cliente->cliente->ClienteId],['estado','ACTIVO']])->count();

                $campo_estado = CampanaCampos::where([['campana_id', $id], ['nombre', 'estado']])->first();

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

                $estados = array_diff($estados, array('PENDIENTE'));

                if ($campaña->estado != 'POR EJECUTAR' or auth()->user()->can('campañas-ejecucion')) {
                    $categorias = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
                    
                    //existe una solicitud PENDIENTE?
                    $solicitud_pendiente = null;

                    $cliente_clienteid = $cliente->cliente_id;
                    $solicitud_atencion = Solicitud::select('solicitudes.id')->join('atencion_clientes', function($join) use($cliente_clienteid){
                        $join->on('solicitudes.atencion_cliente_id', 'atencion_clientes.id')
                        ->where([
                            ["solicitudes.estado","PENDIENTE"],['atencion_clientes.cliente_id',$cliente_clienteid]
                        ]);
                    })->first();

                    if(!empty($solicitud_atencion)){
                        $solicitud_pendiente = $solicitud_atencion->id;
                    }else if(!empty($cliente->solicitud)){
                        $solicitud_pendiente = ($cliente->solicitud->estado == "PENDIENTE")? $cliente->solicitud->id : null;
                    }

                    $ticket = Ticket::where('ClienteId', $cliente_clienteid)->whereNotIn('EstadoDeTicket', array(0))->first();

                    $camposvisualizar = CamposVisualizar::select('campo')->where('campana_id',$id)->get();
                    $campo_vizualizar = array();
                    foreach($camposvisualizar as $campo_ver){
                        array_push($campo_vizualizar , $campo_ver->campo);
                    }
                    
                    if($campaña->tipo == 'FACTURACION'){
                        $cliente_r = Facturacion::where([['ClienteId',$cliente_clienteid],['Periodo' , $campaña->periodo_facturacion]])->first();
                    } 

                    return view('adminlte::campana.llamar',compact(
                        'campaña',
                        'estados',             
                        'cliente',
                        'cliente_r',
                        'categorias',
                        'solicitud_pendiente',
                        'campo_vizualizar',
                        'ticket',
                        'acuerdos_activos'
                    )); 

                }else{
                    abort(403);
                }
            }

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
    public function store(Request $request ,  $id , $cliente)
    {
        if (Auth::user()->can('campañas-respuestas-crear')) {
            
            $rules = [
                'estado_cliente_campana' => 'required' 
            ];
    
            $customMessages = [
                'required' => 'El :attribute es obligatorio',
            ];
    
            $this->validate($request, $rules, $customMessages);

            $resultado = DB::transaction(function () use($request , $id , $cliente) { 

                #traer los campos espesificados para la respuesta de la llamada
                $cantidad_campos = CampanaCampos::where('campana_id',$id)
                                                        ->whereNotIn('nombre', ['Motivo_atencion','Categoria_atencion'])
                                                        ->get();

                #Actualizamos el estado de la llamada para el cliente de la campaña                    
                $campana_cliente = CampanaClientes::find($cliente);
                $campana_cliente->estado = $request->estado_cliente_campana;

                if($request->accion == 'responder'){
                    #Recorrer los campos para realizar su debido registro de la respuesta
                    foreach($cantidad_campos as $campo){                    

                        if(!empty($request[$campo->id])){

                            $respuesta = new CampanaRespuestas();
                            $respuesta->campo_id = $campo->id;
                            $respuesta->campana_cliente_id = $cliente;
                            $respuesta->usuario_id = auth()->user()->id;

                            if($campo->tipo == 'SELECCION_CON_MULTIPLE_RESPUESTA')
                            {
                                $valores = '';
                                foreach($request[$campo->id] as $valor){
                                    $valores.=$valor. ', ';
                                }
                                
                                $valor = rtrim($valores);
                                //$valorF = str_replace(" ",", ",$valor); esto no sirve

                                $respuesta->respuesta = $valor;                                                
                            }
                            elseif($campo->tipo == 'ARCHIVO'){

                                $directory = 'campanas/' . $id . '/' . $cliente;

                                $archivo = Image::make($request->file($campo->id))->encode("jpg", 25);

                                $rutaArchivo = $directory . '/' .$campo->id .'.jpg';

                                Storage::put($rutaArchivo, $archivo);

                                $existe = Storage::exists($rutaArchivo);

                                if($existe){
                                    $respuesta->respuesta = $rutaArchivo;
                                }else{
                                    DB::rollBack();
                                    return ['error', 'Error al guardar el archivo.'];
                                }
                            }else{
                                $respuesta->respuesta = $request[$campo->id];                                                
                            }
                            
                            if(!$respuesta->save()){
                                DB::rollBack();
                                return ['error', 'Error. Uno de los campos esta vacio.'];
                            }
                        }                        
                                                                                                        
                    }
                }elseif($request->accion == 'reagendar'){
                    $campana_cliente->fecha_hora_rellamar = date('Y-m-d H:i:s',  strtotime($request->fecha_hora_rellamar));
                }

                if (!$campana_cliente->save()) {               
                    DB::rollBack();
                    return ['error', 'Error. Al actualizar el estado.'];
                }

                #registramos la observacion si el campo no viene vacio
                if(!empty($request->observacion)){
                    #enviamos al metoso
                    $respuesta_obser = $this->respuestaObservacion($request,$cliente);
                    
                    if(!$respuesta_obser){            
                        DB::rollBack();
                        return ['error', 'Error. Al actualizar la observacion.'];
                    }
                }                

                //Solicitud----------------                
                if($request->motivo != null and $request->categorias != null ){

                    #traemos los campos para la solicutud ('motivo_atencion','categoria_atencion')
                    $campos_solicitud = CampanaCampos::whereIn('nombre',['Motivo_atencion','Categoria_atencion'])->where('campana_id',$id)->get();

                    #aplicamos un foreach para registrar sus datos
                    foreach ($campos_solicitud as  $campo_solicitud) {

                        $campo_sol = ($campo_solicitud->nombre == 'Motivo_atencion')? $request->motivo : $request->categorias;
            
                        $respuesta_camp = $this->respuestaCampo($campo_solicitud,$cliente,$campo_sol);

                        if($respuesta_camp != true){
                            DB::rollBack();
                            return ['error', 'Error. Interno, Reportar al area encargada.'];
                        }
                        
                    }

                    #Validamos que los campos no esten vacidos para poder crear la solicitud.
                    if (!empty($request->celular) && !empty($request->jornada) && !empty($request->fecha_limite)) {
                        
                        $solicitud_atencion = new Solicitud;
                        $solicitud_atencion->atencion_cliente_id = null;
                        $solicitud_atencion->campana_cliente_id = $cliente;
                        $solicitud_atencion->estado = 'PENDIENTE'; 
                        $solicitud_atencion->fecha_hora_solicitud = date('Y-m-d H:i:s');
                        $solicitud_atencion->fecha_limite = $request->fecha_limite;
                        $solicitud_atencion->celular = $request->celular;
                        $solicitud_atencion->correo = $request->correo;
                        $solicitud_atencion->jornada = $request->jornada;
                        $solicitud_atencion->motivo_atencion_id = $request->motivo;
                        $solicitud_atencion->descripcion = $request->observacion;
                        $solicitud_atencion->municipio_id = $campana_cliente->cliente->municipio_id; 
                        
                        if (!$solicitud_atencion->save()) {
                            DB::rollBack(); 
                            return ['error', 'Error al guardar la solicitud!'];
                        }
                    }else{
                        DB::rollBack(); 
                        return ['error', 'Error al guardar la solicitud!'];
                    }
                }
                
                return ['success', 'Informacion registrada correctamente.'];
                
            });

           if($resultado[0] == 'success'){
                return redirect()->route('campanas.show',$id)->with($resultado[0], $resultado[1]);
           }else{
                return redirect()->route('campanas.llamar',[$id, $cliente])->with($resultado[0], $resultado[1]);
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
        if (Auth::user()->can('campañas-respuestas-ver')) {
            
            //consulto las respuestas y observaciones del cliente      
            $dato = CampanaClientes::with(['observaciones','respuestas'])->findOrFail($id);

            // quiero que el registro sino cumple la hora y fecha, este no aparezca en mi vista show,
            // en el momento en que cumpla fecha y hora entonces debe aparecer de nuevo en mi vista
            // show, pero no funciona, entonces que hago
            
            // ->whereNull('fecha_hora_rellamar')
            // ->Where('fecha_hora_rellamar', '=', date('Y-m-d H:i:s'))
            // ->findOrFail($id);
            
            // ->where(function ($query){
            //     $query->whereNull('fecha_hora_rellamar')
            //         ->orwhere('fecha_hora_rellamar', '>=', date('Y-m-d H:i:s'));
            // })
            // ->findOrFail($id);
            
            
            $respuesta = array();   

            if(count($dato->respuestas) > 0){

                $respuesta['respuestas'] = $dato->respuestas;
                $respuesta['usuario'] = $dato->respuestas[0]->usuario->name;

                $campos = array(); 
                $conunt = 0;

                foreach($dato->respuestas as $respuestas){
                    $campos[$conunt] = $respuestas->campo->nombre;
                    $conunt += 1;
                }
                $respuesta['campos'] = $campos; 


            }else{
                $respuesta['respuestas'] = 0 ; 
            }        
            
            if(count($dato->observaciones) > 0){
                $respuesta['observaciones'] = $dato->observaciones;

                $observacion_usuario = array();
                $conunt = 0;
                foreach($dato->observaciones as $observacion){
                    $observacion_usuario[$conunt] = $observacion->usuario->name;
                    $conunt += 1;
                }

                $respuesta['observacion_usuario'] = $observacion_usuario;
                    
            }else{
                $respuesta['observaciones'] = 0 ;
            }

            
            $array = array();
            $array['id'] = $dato->id; 
            $array['nombre_campaña'] = $dato->campana->nombre;
            $array['tipo_campaña'] = $dato->campana->tipo;
            $array['nombre_cliete'] = $dato->cliente->NombreBeneficiario;
            $array['apellido_cliente'] = $dato->cliente->Apellidos;
            $array['documento'] = $dato->cliente->Identificacion;
            $array['correo_cliente'] = $dato->cliente->CorreoElectronico;
            $array['estado'] = $dato->estado;
            if($dato->solicitud != null)
            {
                $array['solicitud'] = $dato->solicitud->id;
            }
            
            $respuesta['datos'] = $array;  
            return response()->json($respuesta);

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
    public function edit()
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
    public function update(Request $request)
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
}
