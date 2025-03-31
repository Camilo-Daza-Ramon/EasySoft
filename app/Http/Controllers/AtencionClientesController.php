<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\MotivoAtencion;
use App\Departamento;
use App\TicketMedioAtencion;
use App\TipoFallo;
use App\TicketTipoPrueba;

use App\AtencionCliente;
use App\Mantenimiento;
use App\MantenimientoCliente;
use App\PQR;
use App\Ticket;
use App\Solicitud;
use App\Cliente;
use DB;
use App\TipoEvento;

use Excel;
use Charts;
use Illuminate\Support\Facades\Log;

class AtencionClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('atencion-clientes-listar')) {

            if (Auth::user()->hasRole('asesor-punto-atencion')) {

                $atenciones = AtencionCliente::select('atencion_clientes.id','atencion_clientes.cliente_id','atencion_clientes.identificacion','atencion_clientes.municipio_id','atencion_clientes.motivo_atencion_id','atencion_clientes.user_id','atencion_clientes.fecha_atencion_agente','atencion_clientes.medio_atencion','atencion_clientes.estado')
                ->join('puntos_atencion_clientes','atencion_clientes.id','=','puntos_atencion_clientes.atencion_cliente_id')
                ->join('puntos_atencion_areas', 'puntos_atencion_clientes.punto_atencion_id','=','puntos_atencion_areas.punto_atencion_id')
                ->join('puntos_atencion_ventanillas', 'puntos_atencion_areas.id','=','puntos_atencion_ventanillas.punto_atencion_area_id')
                ->where([['atencion_clientes.estado','PENDIENTE'],['atencion_clientes.medio_atencion','PUNTO FISICO'],['puntos_atencion_ventanillas.user_id', Auth::user()->id]])
                ->paginate(15);

            }else{
                $atenciones = AtencionCliente::
                Cedula($request->get('cedula'))
                ->Departamento($request->get('departamento'))
                ->Municipio($request->get('municipio'))
                ->Categoria($request->get('categorias'))
                ->Motivo($request->get('motivo'))
                ->Estado($request->get('estado'))
                ->orderBy('id','DESC')
                ->paginate(15);
            }

            $categorias = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();
            $estados = ['PENDIENTE','ATENDIDO','ABANDONO'];

            return view('adminlte::atencion-clientes.index', compact('atenciones','departamentos', 'estados', 'categorias'));


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
        if (Auth::user()->can('atencion-clientes-crear')) {
          $categorias = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
          $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

          $ticket_medios_atencion = TicketMedioAtencion::where('Status', 'A')->get();
          $tipos_fallas = TipoFallo::where([['estado', 'ACTIVO'], ['Uso','FALLO']])->orderBy('DescipcionFallo', 'ASC')->get();

          $medios_atencion = array('PUNTO FISICO','LLAMADA');

          $tipos_pruebas = TicketTipoPrueba::where('estado', 'ACTIVO')->orderBy('Prueba', 'ASC')->get();

          $tipos_eventos = TipoEvento::get();

            return view('adminlte::atencion-clientes.create', compact('categorias', 'departamentos', 'ticket_medios_atencion', 'tipos_fallas','tipos_pruebas','medios_atencion','tipos_eventos'));
        }else{
            abort(403);
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function otro()
    {
        if (Auth::user()->can('atencion-clientes-crear')) {
            $categorias = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
            $departamentos = Departamento::where('Status', 'A')->orderBy('NombreDelDepartamento', 'ASC')->get();

            $ticket_medios_atencion = TicketMedioAtencion::where('Status', 'A')->get();
            $tipos_fallas = TipoFallo::where([['estado', 'ACTIVO'], ['Uso','FALLO']])->orderBy('DescipcionFallo', 'ASC')->get();

            $medios_atencion = array('PUNTO FISICO','LLAMADA');

            $tipos_pruebas = TicketTipoPrueba::where('estado', 'ACTIVO')->orderBy('Prueba', 'ASC')->get();

            return view('adminlte::atencion-clientes.otro', compact('categorias', 'departamentos', 'ticket_medios_atencion', 'tipos_fallas','tipos_pruebas','medios_atencion'));
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
      if (Auth::user()->can('atencion-clientes-crear')) {
          $this->validate(request(),[
              'cedula' => 'required',
              'nombre' => 'required',
              'motivo' => 'required',
              'descripcion' => 'required',
              'solucion' => 'required',
              'estado' => 'required',
              'municipio' => 'required'
          ]);

          $validar = 0;
          $pqr = [];
          $solicitud = false;

          if (!empty($request->ticket) && !empty($request->cliente_id)) {

              $validar = Ticket::where([['TicketId', $request->ticket], ['ClienteId', $request->cliente_id]])->count();

          }

          if (!empty($request->mantenimiento) && !empty($request->cliente_id)) {
              $validar = Mantenimiento::where([['MantId', $request->mantenimiento], ['ClienteId', $request->cliente_id]])->count();

              if ($validar == 0) {
                  $validar = MantenimientoCliente::where([['Mantid', $request->mantenimiento], ['ClienteId', $request->cliente_id]])->count();
              }
          }
          
          if (!empty($request->pqr) && !empty($request->cedula)) {
              $pqr = PQR::select('PqrId')->where([['CUN', $request->pqr], ['IdentificacionCliente', $request->cedula]])->first();

              $validar = count($pqr);
          }
         
          else if (!empty($request->pqr) && !empty($request->cliente_id)) {
              $pqr = PQR::select('PqrId')->where([['CUN', $request->pqr], ['ClienteId', $request->cliente_id]])->first();

              $validar = count($pqr);
          }

          if ($validar == 0) {
              if (!empty($request->celular) && !empty($request->jornada) && !empty($request->fecha_limite)) {
                  $validar = 1;
                  $solicitud = true;
              }
          }

          if ($validar == 0) {
              return response()->json(['error', 'Error. No se especifica un soporte de atención válido!']);
          }else{
              $result = DB::transaction(function () use($request,$pqr,$solicitud) {


                  $atencion = new AtencionCliente;
                  $atencion->identificacion = $request->cedula;
                  $atencion->nombre = $request->nombre;

                  if (!empty($request->cliente_id)) {
                      $atencion->cliente_id = $request->cliente_id;
                  }

                  /*if (!empty($request->codigo)) {
                      $atencion->codigo = $request->codigo;
                  }*/

                  $atencion->motivo_atencion_id = $request->motivo;
                  $atencion->descripcion = $request->descripcion;
                  $atencion->solucion = $request->solucion;
                  $atencion->fecha_atencion_agente = date('Y-m-d H:i:s');
                  $atencion->estado = $request->estado;
                  $atencion->user_id = Auth::user()->id;
                  $atencion->medio_atencion = 'LLAMADA';
                  $atencion->municipio_id = $request->municipio;
                  $atencion->ticket_id = $request->ticket;
                  $atencion->mantenimiento_id = $request->mantenimiento;

                  if (count($pqr) > 0) {
                      $atencion->pqr_id = $pqr->PqrId;
                  }

                  if ($atencion->save()) {

                      if ($solicitud) {

                          $solicitud_atencion = new Solicitud;
                          $solicitud_atencion->atencion_cliente_id = $atencion->id;
                          $solicitud_atencion->estado = 'PENDIENTE';
                          $solicitud_atencion->fecha_hora_solicitud = date('Y-m-d H:i:s');
                          $solicitud_atencion->fecha_limite = $request->fecha_limite;
                          $solicitud_atencion->celular = $request->celular;
                          $solicitud_atencion->correo = $request->correo;
                          $solicitud_atencion->jornada = $request->jornada;
                          $solicitud_atencion->motivo_atencion_id = $request->motivo;
                          $solicitud_atencion->descripcion = $request->descripcion;
                          $solicitud_atencion->municipio_id = $request->municipio;



                          if (!$solicitud_atencion->save()) {
                              DB::rollBack();
                              return ['error', 'Error al guardar la solicitud!'];
                          }
                      }

                      return ['success', 'Atención registrada correctamente!'];

                  }else{
                      DB::rollBack();
                      return ['error', 'Error al guardar la atención!'];
                  }
              });

              return response()->json([$result[0], $result[1]]);
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

        $data = AtencionCliente::findOrFail($id);

        $atencion = array();

        if (count($data) > 0) {

            $atencion['cedula'] = $data->identificacion;
            $atencion['nombre'] = mb_convert_case($data->nombre, MB_CASE_TITLE, "UTF-8");
            $atencion['municipio'] = (!empty($data->municipio_id))? $data->municipio->NombreMunicipio : $data->punto_atencion_cliente->punto_atencion->municipio->NombreMunicipio;

            $atencion['departamento'] = (!empty($data->municipio_id))? $data->municipio->NombreDepartamento : $data->punto_atencion_cliente->punto_atencion->municipio->NombreDepartamento;
            $atencion['motivo'] = (!empty($data->motivo_atencion_id))? $data->motivo_atencion->motivo : $data->punto_atencion_cliente->motivo_categoria;
            $atencion['categoria'] = (!empty($data->motivo_atencion_id))? $data->motivo_atencion->categoria : '';
            $atencion['medio'] = $data->medio_atencion;
            $atencion['agente'] = (!empty($data->user_id))? $data->user->name : '';
            $atencion['fecha'] = date('Y-m-d H:i:s', strtotime(
                                (!empty($data->fecha_atencion_agente))? $data->fecha_atencion_agente : $data->created_at));
            $atencion['estado'] = $data->estado;

            $atencion['solicitud_id'] = (isset($data->solicitud))? $data->solicitud->id : null;
            $atencion['ticket_id'] = $data->ticket_id;
            $atencion['cun'] = (!empty($data->pqr_id))? $data->pqr->CUN : null;
            $atencion['pqr_id'] = $data->pqr_id;
            $atencion['mantenimiento_id'] = $data->mantenimiento_id;
            $atencion['descripcion'] = $data->descripcion;
            $atencion['solucion'] = $data->solucion;

        }

        return response()->json($atencion);

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
     * Show the form for atenter the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atender($id)
    {
        if (Auth::user()->can('atencion-clientes-punto')) {
            $atencion = AtencionCliente::findOrFail($id);

            if ($atencion->punto_atencion_cliente->punto_atencion_id == Auth::user()->punto_atencion_ventanilla->punto_atencion_area->punto_atencion_id) {
                // code...

                if($atencion->estado == 'ATENDIDO'){
                    abort(403);
                }else{
                    $categorias = MotivoAtencion::select('categoria')->where('estado','ACTIVO')->groupBy('categoria')->get();
                    $departamentos = Departamento::where('Status', 'A')->orderBy('NombreDelDepartamento', 'ASC')->get();
                    $ticket_medios_atencion = TicketMedioAtencion::where('Status', 'A')->get();
                    $tipos_fallas = TipoFallo::where([['estado', 'ACTIVO'], ['Uso','FALLO']])->orderBy('DescipcionFallo', 'ASC')->get();

                    $municipio_id = $atencion->punto_atencion_cliente->punto_atencion->municipio_id;
                    $departamento_id = $atencion->punto_atencion_cliente->punto_atencion->municipio->DeptId;

                    $tipos_pruebas = TicketTipoPrueba::where('estado', 'ACTIVO')->orderBy('Prueba', 'ASC')->get();

                    $tipos_eventos = TipoEvento::get();

                    return view('adminlte::atencion-clientes.atender', compact('categorias','departamentos','atencion','municipio_id','tipos_fallas','departamento_id','ticket_medios_atencion','tipos_pruebas','tipos_eventos'));
                }
            }else{
                abort(403);
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



        if (Auth::user()->can('atencion-clientes-punto')) {

          $this->validate(request(),[
              'cedula_atencion' => 'required',
              'nombre' => 'required',
              'motivo' => 'required',
              'descripcion' => 'required',
              'solucion' => 'required',
              'estado' => 'required',
              'municipio' => 'required'
          ]);

          $validar = 0;
          $pqr = [];
          $solicitud = false;

          if (!empty($request->ticket) && !empty($request->cliente_id)) {

              $validar = Ticket::where([['TicketId', $request->ticket], ['ClienteId', $request->cliente_id]])->count();

          }

          if (!empty($request->mantenimiento) && !empty($request->cliente_id)) {
              $validar = Mantenimiento::where([['MantId', $request->mantenimiento], ['ClienteId', $request->cliente_id]])->count();

              if ($validar == 0) {
                  $validar = MantenimientoCliente::where([['Mantid', $request->mantenimiento], ['ClienteId', $request->cliente_id]])->count();
              }
          }

          if (!empty($request->pqr) && !empty($request->cliente_id)) {
              $pqr = PQR::select('PqrId')->where([['CUN', $request->pqr], ['ClienteId', $request->cliente_id]])->first();

              $validar = count($pqr);
          }


          if ($validar == 0) {
              if (!empty($request->celular) && !empty($request->jornada) && !empty($request->fecha_limite)) {
                  $validar = 1;
                  $solicitud = true;
              }
          }

          if ($validar == 0) {
              return response()->json(['error', 'Error. No se especifica un soporte de atención válido!']);
          }else{

            $result = array();

            $atencion = AtencionCliente::find($id);

            if ($atencion->punto_atencion_cliente->punto_atencion_id == Auth::user()->punto_atencion_ventanilla->punto_atencion_area->punto_atencion_id) {

                if (!empty($request->cedula_titular)) {
                    $atencion->identificacion_titular = $request->cedula_titular;
                }

                $atencion->nombre = $request->nombre;

                if (!empty($request->cliente_id)) {
                    $atencion->cliente_id = $request->cliente_id;
                }else{

                    $traer_id_cliente = Cliente::select('ClienteId')
                        ->where('Identificacion', $request->cedula_titular)
                        ->orWhere('Identificacion', $request->cedula_atencion)
                        ->first();

                    if (count($traer_id_cliente) > 0) {
                        $atencion->cliente_id = $traer_id_cliente->ClienteId;
                    }
                }

                /*if (!empty($request->codigo)) {
                    $atencion->codigo = $request->codigo;
                }*/

                $atencion->motivo_atencion_id = $request->motivo;
                $atencion->descripcion = $request->descripcion;
                $atencion->solucion = $request->solucion;
                $atencion->fecha_atencion_agente = date('Y-m-d H:i:s');
                $atencion->estado = $request->estado;
                $atencion->user_id = Auth::user()->id;
                $atencion->municipio_id = $request->municipio;
                $atencion->municipio_id = $request->municipio;
                $atencion->ticket_id = $request->ticket;
                $atencion->mantenimiento_id = $request->mantenimiento;

                if (count($pqr) > 0) {
                    $atencion->pqr_id = $pqr->PqrId;
                }
                if ($atencion->save()) {
                  if ($solicitud) {
                    $solicitud_atencion = new Solicitud;
                    $solicitud_atencion->atencion_cliente_id = $atencion->id;
                    $solicitud_atencion->estado = 'PENDIENTE';
                    $solicitud_atencion->fecha_hora_solicitud = date('Y-m-d H:i:s');
                    $solicitud_atencion->fecha_limite = $request->fecha_limite;
                    $solicitud_atencion->celular = $request->celular;
                    $solicitud_atencion->correo = $request->correo;
                    $solicitud_atencion->jornada = $request->jornada;
                    $solicitud_atencion->motivo_atencion_id = $request->motivo;
                    $solicitud_atencion->descripcion = $request->descripcion;
                    $solicitud_atencion->municipio_id = $request->municipio;

                    if (!$solicitud_atencion->save()) {
                        $result = ['error', 'Error al guardar la solicitud!'];
                    }
                  }

                  $result = ['success', 'Atención registrada correctamente!'];

                }else{
                  $result = ['error', 'Error al guardar la atención!'];
                }

                return response()->json([$result[0], $result[1]]);

            }else{
                abort(403);
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

        if (Auth::user()->can('atencion-clientes-exportar')) {
            Excel::create('atencion-clientes', function($excel) use($request) {

                $excel->sheet('Listado', function($sheet) use($request) {

                    $datos = array();

                    $atenciones = AtencionCliente::
                    Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Categoria($request->get('categorias'))
                    ->Motivo($request->get('motivo'))
                    ->Estado($request->get('estado'))
                    ->get();

                    foreach ($atenciones as $key) {

                        $date1 = new \DateTime($key->created_at);

                        $fecha2 = (!empty($key->fecha_atencion_agente))? date('Y-m-d H:i:s', strtotime($key->fecha_atencion_agente)) : date('Y-m-d H:i:s');

                        $date2 = new \DateTime($fecha2);

                        $diferencia = $date1->diff($date2);

                        $datos[] = array(
                            'ID' => $key->id,
                            'CEDULA' => $key->identificacion,
                            'NOMBRE' => $key->nombre,
                            'CEDULA-TITULAR' => (!empty($key->cliente_id))? $key->cliente->Identificacion : $key->identificacion_titular,
                            'NOMBRE TITULAR' => (!empty($key->cliente_id))? mb_convert_case($key->cliente->NombreBeneficiario.' '. $key->cliente->Apellidos, MB_CASE_TITLE, "UTF-8"): '',
                            'MEDIO ATENCION' => $key->medio_atencion,
                            'PROYECTO' => (!empty($key->cliente_id))? $key->cliente->proyecto->NumeroDeProyecto : '',
                            'CATEGORIA-ATENCION' => (!empty($key->motivo_atencion_id))? $key->motivo_atencion->categoria : '',
                            'MOTIVO-ATENCION' => (!empty($key->motivo_atencion_id))? $key->motivo_atencion->motivo : $key->punto_atencion_cliente->motivo_categoria,
                            'MUNICIPIO' => (!empty($key->municipio_id))? $key->municipio->NombreMunicipio : $key->punto_atencion_cliente->punto_atencion->municipio->NombreMunicipio,
                            'DEPARTAMENTO' => (!empty($key->municipio_id))? $key->municipio->NombreDepartamento : $key->punto_atencion_cliente->punto_atencion->municipio->NombreDepartamento,
                            'DESCRIPCION' => $key->descripcion,
                            'SOLUCION' => $key->solucion,

                            'FECHA SOCILITUD DE LA ATENCION' => date('Y-m-d H:i:s', strtotime($key->created_at)),

                            'FECHA ATENCION AGENTE' => (!empty($key->fecha_atencion_agente))? date('Y-m-d H:i:s', strtotime($key->fecha_atencion_agente)) : '',

                            'TIEMPO DE ATENCION (En minutos)' => ($diferencia->d * 1440) + ($diferencia->h * 60) + ($diferencia->i + $diferencia->s/60),
                            'AGENTE' => (!empty($key->user_id))? $key->user->name : '',
                            'CUN-TICKET' => $key->codigo,
                            'ESTADO' => $key->estado
                        );
                    }


                    if (count($datos) == 0) {
                        return redirect()->route('atencion-clientes.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);

                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }

    public function estadisticas(Request $request){

        if (Auth::user()->can('atencion-clientes-estadisticas')) {        
            /*$atenciones = Solicitud::select('ma.motivo', 'solicitudes.id')
                ->join('atencion_clientes as ac', 'solicitudes.atencion_cliente_id', 'ac.id')
                ->join('motivos_atencion as ma', 'ac.motivo_atencion_id', 'ma.id')
                ->where('solicitudes.estado', 'PENDIENTE')
                ->get();*/

            $sumatoria_cumplimiento = Solicitud::Mes($request->get('mes'))->selectRaw("COUNT( CASE WHEN (DATEDIFF(DAY, fecha_limite, fecha_hora_atendida) > 0) THEN 1 END ) as total_incumplida, 
            COUNT( CASE WHEN (DATEDIFF(DAY, fecha_limite, fecha_hora_atendida) <= 0) THEN 1 END ) as total_cumplida")
            ->where('estado', 'ATENDIDA')
            ->first();

            $estados = Solicitud::Mes($request->get('mes'))->select('estado')->get();

            $grafica = Charts::database($estados, 'pie', 'highcharts')
                ->title('Estados de las Solicitudes')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('estado');

            $atenciones2 = Solicitud::Mes($request->get('mes'))->selectRaw('ma.id as motivo_id,ma.motivo, COUNT(solicitudes.id) as cantidad')
                ->leftJoin('atencion_clientes as ac', 'solicitudes.atencion_cliente_id', 'ac.id')
                ->leftJoin('campanas_clientes as cc', 'solicitudes.campana_cliente_id', 'cc.id')
                ->leftJoin('motivos_atencion as ma', 'solicitudes.motivo_atencion_id', 'ma.id')
                ->whereIn('solicitudes.estado', ['PENDIENTE', 'VENCIDA'])
                ->orderBy('cantidad', 'ASC')
                ->groupBy(['motivo','ma.id'])
                ->get();

            //dd($atenciones2);

            return view('adminlte::atencion-clientes.estadisticas', compact('grafica','atenciones2', 'sumatoria_cumplimiento'));
        }else{
            abort(403);
        }
    }
}
