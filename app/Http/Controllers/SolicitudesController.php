<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Solicitud;
use App\MotivoAtencion;
use App\Departamento;
use App\TicketMedioAtencion;
use App\TipoFallo;
use App\TicketTipoPrueba;
use App\TipoEvento;
use App\PQR;
use App\Ticket;
use App\Mantenimiento;

use Excel;


class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('solicitudes-listar')) {

            $solicitudes = Solicitud::latest() 
              ->Cedula($request->get('cedula'))
              ->Departamento($request->get('departamento'))
              ->Municipio($request->get('municipio'))
              ->Categoria($request->get('categorias'))
              ->Motivo($request->get('motivo'))
              ->Estado($request->get('estado')) 
              ->orderBy('estado', 'DESC')
              ->orderBy('fecha_hora_solicitud', 'ASC')
              ->paginate(15);
            
            $categorias = MotivoAtencion::select('id','categoria','motivo')->where('estado','ACTIVO')->get();
            $categoriass = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $estados = ['PENDIENTE','ATENDIDA','VENCIDA'];

            return view('adminlte::atencion-clientes.solicitudes.index', compact('solicitudes','categorias','categoriass','departamentos','estados'));

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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->can('solicitudes-ver')) {

            $solicitud = Solicitud::findOrFail($id); 
            
            if($solicitud->atencion != null){
              $atencion = $solicitud->atencion; 
            }else{
              $atencion = $solicitud->campana_cliente;
            }

            $categorias = MotivoAtencion::select('id','categoria','motivo')->where('estado','ACTIVO')->get();
            

            $ticket_medios_atencion = TicketMedioAtencion::where('Status', 'A')->get();
            $tipos_fallas = TipoFallo::where([['estado', 'ACTIVO'], ['Uso','FALLO']])->orderBy('DescipcionFallo', 'ASC')->get();

            $medios_atencion = array('PUNTO FISICO','LLAMADA');

            $tipos_pruebas = TicketTipoPrueba::where('estado', 'ACTIVO')->orderBy('Prueba', 'ASC')->get();

            $tipos_eventos = TipoEvento::get();


            return view('adminlte::atencion-clientes.solicitudes.show', compact('solicitud','atencion','ticket_medios_atencion','tipos_fallas','medios_atencion','tipos_pruebas','tipos_eventos','categorias'));
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
        if (Auth::user()->can('solicitudes-actualizar')) {
          $solicitud = Solicitud::find($id);
          
          if($solicitud->atencion != null){
            $atencion = $solicitud->atencion; 
          }else{
            $atencion = $solicitud->campana_cliente;
          }
          $validar = 0; 

          if (!empty($request->cun)) {            
            if ($solicitud->atencion_cliente_id != null){
              $identificacion = $atencion->identificacion;
            }else{
              $identificacion = $atencion->cliente->Identificacion;
            }             
            $pqr = PQR::select('PqrId')->where([['IdentificacionCliente', $identificacion],['CUN', $request->cun]])->first();
            $validar = count($pqr);
          }

          if (!empty($request->ticket)) {
            
            if ($solicitud->atencion_cliente_id != null){
              $cliente_id = $atencion->cliente_id;
            }else{
              $cliente_id = $atencion->cliente->ClienteId;
            } 
            $validar = Ticket::where([['TicketId', $request->ticket],['ClienteId', $cliente_id]])->count();
          }

          if (!empty($request->mantenimiento)) {
            if ($solicitud->atencion_cliente_id != null){
              $cliente_id = $atencion->cliente_id;
            }else{
              $cliente_id = $atencion->cliente->ClienteId;
            } 
            $validar = Mantenimiento::where([['MantId', $request->mantenimiento],['ClienteId', $cliente_id]])->count();
          }

          if ($validar == 0) {
            return redirect()->route('solicitudes.show',$id)->with('error','Los datos suministrados no coinciden con el cliente asociado.');
          }else{

            $solicitud->fecha_hora_atendida = date('Y-m-d H:i:s');
            $solicitud->estado = 'ATENDIDA';
            $solicitud->user_id = Auth::user()->id;
            if ($solicitud->save()) {
              $atencion->ticket_id = $request->ticket;
              $atencion->mantenimiento_id = $request->mantenimiento;
              $atencion->pqr_id = (isset($pqr)? $pqr->PqrId : null);

              if ($atencion->save()) {
                return redirect()->route('solicitudes.index')->with('success','Solicitud atendida.');
              }else{
                return redirect()->route('solicitudes.index')->with('error','Error al actualizar la atención!');
              }
            }else{
              return redirect()->route('solicitudes.index')->with('error','Error al actualizar la solicitud!');
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
        // 
    }

    public function exportar(Request $request){

        if (Auth::user()->can('solicitudes-exportar')) {
            Excel::create('solicitudes', function($excel) use($request) {

                $excel->sheet('Listado', function($sheet) use($request) {

                    $datos = array();

                    $solicitudes = Solicitud::
                    Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Categoria($request->get('categorias'))
                    ->Motivo($request->get('motivo'))
                    ->Estado($request->get('estado'))
                    ->get();

                    foreach ($solicitudes as $solicitud) {

                      //$date1 = new \DateTime( (!empty($solicitud->fecha_hora_atendida))? date('Y-m-d H:i:s', strtotime($solicitud->fecha_hora_atendida)) : date('Y-m-d H:i:s'));
                      $date1 = new \DateTime( (!empty($solicitud->fecha_hora_atendida))? date('Y-m-d H:i:s', strtotime($solicitud->fecha_hora_atendida)) : date('Y-m-d H:i:s'));
                      $fecha2 = date('Y-m-d H:i:s', strtotime($solicitud->fecha_limite));
                      $date2 = new \DateTime($fecha2);
                      $diferencia = $date1->diff($date2);

                      $datos[] = array(
                          'ID' => $solicitud->id,
                          'CEDULA' => ($solicitud->atencion_cliente_id == null)? $solicitud->campana_cliente->cliente->Identificacion : $solicitud->atencion->identificacion,
                          'CEDULA-TITULAR' => (!empty($solicitud->atencion->cliente_id))? $solicitud->atencion->cliente->Identificacion : $solicitud->identificacion_titular,
                          'MEDIO ATENCION' => ($solicitud->atencion_cliente_id == null)? 'LLAMADA DE CAMPAÑA' : $solicitud->atencion->medio_atencion,
                          'PROYECTO' => (!empty($solicitud->atencion->cliente_id))? $solicitud->atencion->cliente->proyecto->NumeroDeProyecto : '',
                          'CATEGORIA-ATENCION' => $solicitud->motivo_atencion->categoria,
                          'MOTIVO-ATENCION' => $solicitud->motivo_atencion->motivo,                            
                          'MUNICIPIO' => $solicitud->municipio->NombreMunicipio,
                          'DEPARTAMENTO' => $solicitud->municipio->NombreDepartamento,
                          'DESCRIPCION' => $solicitud->descripcion,
                          'SOLUCION' => ($solicitud->atencion_cliente_id != null)? $solicitud->atencion->solucion : '',
                          'JORNADA' => $solicitud->jornada,
                          'FECHA SOCILITUD' => date('Y-m-d H:i:s', strtotime($solicitud->fecha_hora_solicitud)),
                          'FECHA LIMITE' => date('Y-m-d H:i:s', strtotime($solicitud->fecha_limite)),
                          'TIEMPO RESTANTE EN DIAS' => ($diferencia->invert > 0)? 0: $diferencia->days,
                          'TIEMPO SIN SOLUCION' => ($diferencia->invert > 0)? $diferencia->days: 0,
                          //'TIEMPO RESTANTE EN DIAS' => ($diferencia->days >= 0)? $diferencia->days: 0,
                          //'TIEMPO SIN SOLUCION' => ($diferencia->days < 0)? $diferencia->days * (-1): 0,
                          'FECHA ATENCION AGENTE' => (!empty($solicitud->fecha_hora_atendida))? date('Y-m-d H:i:s', strtotime($solicitud->fecha_hora_atendida)) : '',
                          'AGENTE' => (!empty($solicitud->user_id))? $solicitud->user->name : '',
                          'CUN' => (isset($solicitud->atencion->pqr)) ? $solicitud->atencion->pqr->CUN : '',
                          'TICKET' => (isset($solicitud->atencion->ticket_id)) ? $solicitud->atencion->ticket_id : '',
                          'MANTENIMIENTO' => (isset($solicitud->atencion->mantenimiento_id)) ? $solicitud->atencion->mantenimiento_id : '',
                          'ESTADO' => $solicitud->estado
                      );
                    }


                    if (count($datos) == 0) {
                        return redirect()->route('solicitudes.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);
                    
                    $sheet->fromArray($datos, true, 'A1', true);

                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }
}
