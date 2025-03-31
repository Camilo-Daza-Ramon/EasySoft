<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Custom\ActaTraslado;
use App\PQR;
use App\Cliente;
use App\Proyecto;
use App\ArchivoCliente;
use App\Departamento;
use App\User;

use PDF;
use Carbon\Carbon;
use Storage;
use DB;
use Excel;

use App\TicketMedioAtencion;
use App\TipoPqr;
use App\Evento;

class PQRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('pqrs-listar')) {
            $pqrs = PQR::
            Cedula($request->get('documento'))
            ->CUN($request->get('cun'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            ->Estado($request->get('estado'))
            ->orderBy('Status', 'ASC')
            ->orderBy('FechaApertura', 'ASC')
            ->where(function ($query) {
                if(Auth::user()->proyectos()->count() > 0){
                    $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('proyecto_id'));
                }
            })
            ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
            ->where(function ($query) {
                if(Auth::user()->proyectos()->count() > 0){
                    $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                }
            })
            ->get();

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();


            $estados = PQR::select('Status')->groupBy('Status')->get();            

            return view('adminlte::pqr.index', compact('pqrs', 'proyectos','estados', 'departamentos'));

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
        if (Auth::user()->can('pqrs-crear')) {

            $pqr = new PQR();

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            $tipos_pqrs = TipoPqr::select('ClasificacionPqr as tipo')->groupBy('ClasificacionPqr')->orderBy('ClasificacionPqr', 'ASC')->get();

            $eventos = Evento::orderBy('TipoEvento', 'ASC')->get();

            $agentes = User::select('id', 'name')->orderBy('name', 'ASC')->get();

            $prioridades = [
                "1" => "Completa pérdida del servicio de internet.",
                "2" => "Intermitencia o Lentitud.",
                "3" => "Aclaración a dudas sobre la prestación del servicio.",
                "4" => "Traslado"
            ];

            $medios_atencion = TicketMedioAtencion::where('Status', 'A')->orderBy('Descripcion', 'ASC')->get();

            $estados = ["ABIERTO", "CERRADO"];

            $accion = "CREAR";


            return view('adminlte::pqr.create', compact(
                'pqr',
                'prioridades', 
                'medios_atencion',
                'tipos_pqrs',
                'eventos',
                'estados',
                'departamentos',
                'agentes',
                'accion'
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
      if (Auth::user()->can('pqrs-crear')) {
       
            $anno = substr(date('y'),-2);
            $consecutivo = PQR::select('PqrId')->where([['FechaApertura','>=', date('Y') . '-01-01 00:00:00']])->count();

            $pqr = new PQR;
            $pqr->CUN = '3563-'.$anno.'-'.str_pad(($consecutivo+1), 10, "0", STR_PAD_LEFT);
            $pqr->Prioridad = $request->prioridad;
            $pqr->TipoEntrada = $request->canal_atencion;
            $pqr->TipoSolicitud = $request->tipo_solicitud;
            $pqr->TipoDeEvento = $request->tipo_evento;

            $pqr->Hechos = $request->hechos;
            $pqr->Solucion = $request->solucion;
            $pqr->Observacion = $request->observaciones;

            $pqr->TipoTicket = $request->clasificacion;

            $pqr->MunicipioId = $request->municipio;

            $cliente = Cliente::select('ClienteId as id', 'ProyectoId as proyecto_id')->where('Identificacion', $request->cedula)->first();

            if(isset($cliente->cedula)){
                $pqr->ClienteId = $cliente->id;
                $pqr->ProyectoId = $cliente->proyecto_id;
            }

            $pqr->NombreBeneficiario = $request->nombre;
            $pqr->IdentificacionCliente = $request->cedula;
            $pqr->CorreoElectronico = $request->correo;
            $pqr->DireccionNotificacion = $request->direccion;
            $pqr->NumeroDeTelefono = $request->telefono;
            $pqr->NumeroDeCelular = $request->celular;

            $pqr->AvisoDePrivacidad = ($request->tratamiento_datos)? 'SI' : 'NO';
            $pqr->AutorizaTratamientoDatos = ($request->tratamiento_datos)? 'SI' : 'NO';

            $pqr->FechaApertura = date('Y-m-d H:i:s');
            $pqr->FechaEstimada = (!empty($request->fecha_limite))? str_replace("T", " ", $request->fecha_limite) : null;
            $pqr->FechaMaxima = (!empty($request->fecha_limite))? str_replace("T", " ", $request->fecha_limite) : null;
            $pqr->FechaCierre = (!empty($request->fecha_cierre))? str_replace("T", " ", $request->fecha_cierre) : null;

            $pqr->MarcaTiempo = date('Y-m-d H:i:s');

            $pqr->user_crea = Auth::user()->id;
            $pqr->Status = "ABIERTO";

            if ($pqr->save()) {
                if ($request->ajax()) {
                    return response()->json(['success', 'PQR creada correctamente.', $pqr->CUN]);
                }else{
                    return redirect()->route('pqr.show', $pqr->PqrId)->with('success', 'PQR creada correctamente.');
                }
            }else{
                if ($request->ajax()) {
                    return response()->json(['error', 'Error al crear la PQR!', 0]);
                }else{
                    return redirect()->route('pqrs.create')->with('error', 'Error al crear la PQR');
                }
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
        if (Auth::user()->can('pqrs-ver')) {

            $pqr = PQR::findOrFail($id);

            if(Auth::user()->proyectos()->count() > 0){

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if(!in_array($pqr->ProyectoId, $array)){
                    abort(403);
                }                
            }

            $fecha_hora_inicio = (!empty($pqr->FechaApertura))? date('Y-m-d H:i:s', strtotime($pqr->FechaApertura)) : null;

            $fecha_hora_fin = (!empty($pqr->FechaCierre)) ? date('Y-m-d H:i:s', strtotime($pqr->FechaCierre)) : date('Y-m-d H:i:s');

            $indisponibilidad = $this->indisponibilidad($fecha_hora_inicio, $fecha_hora_fin);

            return view('adminlte::pqr.show', compact('pqr', 'indisponibilidad'));
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
        if (Auth::user()->can('pqrs-editar')) {

            $pqr = PQR::findOrFail($id);

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();


            $tipos_pqrs = TipoPqr::select('ClasificacionPqr as tipo')->groupBy('ClasificacionPqr')->orderBy('ClasificacionPqr', 'ASC')->get();

            $eventos = Evento::orderBy('TipoEvento', 'ASC')->get();

            $agentes = User::select('id', 'name')->orderBy('name', 'ASC')->get();

            $prioridades = [
                "1" => "Completa pérdida del servicio de internet.",
                "2" => "Intermitencia o Lentitud.",
                "3" => "Aclaración a dudas sobre la prestación del servicio.",
                "4" => "Traslado"
            ];

            $medios_atencion = TicketMedioAtencion::where('Status', 'A')->orderBy('Descripcion', 'ASC')->get();

            $estados = ["ABIERTO", "CERRADO"];

            $accion = "EDITAR";


            return view('adminlte::pqr.edit', compact(
                'pqr',
                'prioridades', 
                'medios_atencion',
                'tipos_pqrs',
                'eventos',
                'estados',
                'departamentos',
                'agentes',
                'accion'
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
    public function update(Request $request, $id)
    {

        if(Auth::user()->can('pqrs-editar')){

            $pqr = PQR::find($id);

            $pqr->Prioridad = $request->prioridad;
            $pqr->TipoEntrada = $request->canal_atencion;
            $pqr->TipoSolicitud = $request->tipo_solicitud;
            $pqr->TipoDeEvento = $request->tipo_evento;

            $pqr->Hechos = $request->hechos;
            $pqr->Solucion = $request->solucion;
            $pqr->Observacion = $request->observaciones;

            $pqr->TipoTicket = $request->clasificacion;

            $pqr->MunicipioId = $request->municipio;

            $cliente = Cliente::select('ClienteId as id', 'ProyectoId as proyecto_id')->where('Identificacion', $request->cedula)->first();

            if(isset($cliente->cedula)){
                $pqr->ClienteId = $cliente->id;
                $pqr->ProyectoId = $cliente->proyecto_id;
            }            

            $pqr->NombreBeneficiario = $request->nombre;
            $pqr->IdentificacionCliente = $request->cedula;
            $pqr->CorreoElectronico = $request->correo;
            $pqr->DireccionNotificacion = $request->direccion;
            $pqr->NumeroDeTelefono = $request->telefono;
            $pqr->NumeroDeCelular = $request->celular;

            $pqr->AvisoDePrivacidad = ($request->tratamiento_datos)? 'SI' : 'NO';
            $pqr->AutorizaTratamientoDatos = ($request->tratamiento_datos)? 'SI' : 'NO';

            $pqr->FechaApertura = str_replace("T", " ", $request->fecha_apertura);
            $pqr->FechaEstimada = (!empty($request->fecha_limite))? str_replace("T", " ", $request->fecha_limite) : null;
            $pqr->FechaMaxima = (!empty($request->fecha_limite))? str_replace("T", " ", $request->fecha_limite) : null;
            $pqr->FechaCierre = (!empty($request->fecha_cierre))? str_replace("T", " ", $request->fecha_cierre) : null;

            $pqr->MarcaTiempo = str_replace("T", " ", $request->fecha_apertura);

            $pqr->user_crea = $request->creado_por;
            $pqr->user_cerro = $request->cerrado_por;
            $pqr->Status = $request->estado;

            if($pqr->save()){
                return redirect()->route('pqr.show', $id)->with('success', 'PQR actualizada correctamente.');
            }else{
                return redirect()->route('pqrs.edit', $id)->with('error', 'Error al actualizar la PQR');
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
        if(Auth::user()->can('pqrs-eliminar')){

            return redirect()->route('pqr.index')->with('warning', 'No esta permitido eliminar PQRS.');

            /*$pqr = PQR::find($id);

            if($pqr->archivos->count() > 0){
                return redirect()->route('pqr.index')->with('warning', 'No es posible eliminar porque tiene archivos relacionados.');
            }

            if($pqr->paradas_reloj->count() > 0){
                return redirect()->route('pqr.index')->with('warning', 'No es posible eliminar porque tiene paradas de reloj relacionadas.');
            }

            if($pqr->delete()){
                return redirect()->route('pqr.index')->with('success', 'PQR eliminada correctamente.');
            }else{
                return redirect()->route('pqr.index')->with('error', 'Error al eliminar la PQR');
            }*/
        }else{
            abort(403);
        }
    }

    private function indisponibilidad($fecha_hora_inicio,$fecha_hora_fin){

        $indisponibilidad = [
            'dias' => '',
            'horas' => '',
            'minutos' => ''
        ];

        if (!empty($fecha_hora_inicio) && !empty($fecha_hora_fin)) {
        
            $contador = date_diff(date_create($fecha_hora_inicio), date_create($fecha_hora_fin));

            $indisponibilidad['dias'] = $contador->days; //$contador->format('%a');
            $indisponibilidad['minutos'] = ($contador->days * 1440) + ($contador->h * 60) + $contador->i;
            $indisponibilidad['horas'] = $indisponibilidad['minutos'] /60; //$contador->format('%h') +
        }

        return $indisponibilidad;
    }

    public function acta_traslado_generar($pqr_id){

        $pqr = PQR::findOrFail($pqr_id);

        $datos = DB::select('SELECT * from datos_acta_traslado(?)', [$pqr->CUN]);

        foreach ($datos as $data) {
            // code...

            $codigo_dane = $data->dane;
            $cun = $data->cun;
            $id_punto = $data->idpunto;

            //$date = $data->fecha;
            $fecha = date('Y-m-d', strtotime($data->fecha));

            $municipio = $data->municipio;
            $departamento = $data->departamento;             
            
            $tecnico_nombre = "William Ricardo Cárdenas Martínez";
            $tecnico_cedula = 80845408;
            $tecnico_celular = "3233892343";
            $tecnico_firma = "img\\firma_tecnico.jpg";

            $cliente_nombre = $data->cliente_nombre;
            $cliente_cedula = $data->cliente_cedula;
            $cliente_celular = $data->cliente_celular;
            $cliente_correo = $data->cliente_correo;

            $observaciones = $data->observaciones;

            $cabecera = array("DIRECCIÓN", "Cooordenadas GPS", "ESTRATO", "TIPO BENEFICIARIO");
            $direcciones = array();
            
            $array = array();
            $array["direccion"] = $data->cliente_direccion;
            $array["coordenadas"] = number_format($data->latitud,5,'.','').','.number_format($data->longitud,5,'.','');
            $array["estrato"] = $data->cliente_estrato;
            $array["tipo_beneficiario"] = $data->tipo_beneficiario;
            $direcciones[] = $array;
            $clientes_archivos = ArchivoCliente::where([['ClienteId', ($pqr->ClienteId !== null ?  $pqr->ClienteId : $data->id)], ['nombre', 'firma']])->first();
            $cliente_firma = $clientes_archivos !== null ? $clientes_archivos->archivo : '';

            $pdf = new ActaTraslado('P','mm',array(215.91,347.3));
                  
            $pdf->AddFont('calibri','','calibri.php');
            $pdf->AddFont('futura-bdcn-bt-bold','','futura-bdcn-bt-bold.php');
            $pdf->AddFont('futura-md-bt','','futura-md-bt.php');
            $pdf->AddFont('futura-md-bt-bold','', 'futura-md-bt-bold.php');

            $pdf->AliasNbPages();
            
            // //Primera página
            $pdf->AddPage();
            
            //margen del pie de pagina
            $pdf->SetAutoPageBreak(true,5);

            #pagina 1
            $pdf->informacion_general($codigo_dane,$cun,$id_punto,$fecha,$departamento,$municipio);
            $pdf->tecnico($tecnico_nombre,$tecnico_cedula,$tecnico_celular);
            $pdf->cliente($cliente_nombre,$cliente_cedula,$cliente_celular,$cliente_correo);

            $pdf->direcciones();
            $pdf->BasicTable($cabecera,$direcciones,false,[80,null,null,null]);

            $pdf->tipo_tecnologia();
            
            $pdf->servicio_activo();

            $pdf->observaciones_generales($observaciones);
            $pdf->firmas($cliente_cedula,$cliente_nombre,$cliente_firma,$tecnico_nombre,$tecnico_cedula,$tecnico_firma);

            $acta = $pdf->Output('D', 'Acta_Traslado_'.$data->cun.'.pdf');

            return $acta;
        }
    }

    public function exportar(Request $request){
        if(Auth::user()->can('pqrs-exportar')){
            Excel::create('PQRS', function($excel) use($request) {
 
                $excel->sheet('PQRS', function($sheet) use($request) {    
                   
                    $datos = array();
    
                    $pqrs = PQR::
                    Cedula($request->get('documento'))
                    ->CUN($request->get('cun'))
                    ->Proyecto($request->get('proyecto'))
                    ->Departamento($request->get('departamento'))
                    ->Municipio($request->get('municipio'))
                    ->Estado($request->get('estado'))
                    ->get();                
    
                    foreach ($pqrs as $pqr) {

                        $tiempo_total_indisponibilidad = 0;
                        $tiempo_total_paradas = 0;

                        $fecha1 = new \DateTime($pqr->FechaApertura);
                        $fecha2 = new \DateTime($pqr->FechaCierre);

                        $intervalo = $fecha1->diff($fecha2);

                        $tiempo_total_indisponibilidad = $intervalo->days * 24 * 60 + $intervalo->h * 60 + $intervalo->i;

                        if(isset($pqr->paradas_reloj)){
                            foreach ($pqr->paradas_reloj as $parada_reloj) {
                                $fecha1 = new \DateTime($parada_reloj->InicioParadaDeReloj .' '. $parada_reloj->InicioParada);
                                $fecha2 = new \DateTime($parada_reloj->FinParadaDeReloj .' '. $parada_reloj->FinParada);
                                $intervalo = $fecha1->diff($fecha2);

                                $tiempo_total_paradas += $intervalo->days * 24 * 60 + $intervalo->h * 60 + $intervalo->i;
                            }
                        }

                        $datos[] = array(
                            'Cliente-ID' => $pqr->ClienteId,
                            'ID-Punto' => (isset($pqr->cliente->meta_cliente))? $pqr->cliente->meta_cliente->idpunto : '',
                            'Cun' => $pqr->CUN,
                            'Prioridad' => $pqr->Prioridad,
                            'Canal De Contacto' => (!empty($pqr->TipoEntrada))? $pqr->medio_atencion->Descripcion : '',
                            'Tipo Pqr' => $pqr->TipoSolicitud,
                            'Persona Que Reporta' => $pqr->NombreBeneficiario,
                            'Cédula' => $pqr->IdentificacionCliente,
                            'Telefonos' => $pqr->NumeroDeTelefono,
                            'Celular' => $pqr->NumeroDeCelular,
                            'Direccion De Correspondencia' => $pqr->DireccionNotificacion,
                            'Barrio' => (!empty($pqr->ClienteId))? $pqr->cliente->Barrio : null,
                            'Municipio' => (!empty($pqr->MunicipioId))? $pqr->municipio->NombreMunicipio : '',
                            'Codigo Dane Municipio' => $pqr->CodigoDane,
                            'Departamento' => (!empty($pqr->MunicipioId))? $pqr->municipio->departamento->NombreDelDepartamento : '',
                            'Codigo Dane Departamento' => (!empty($pqr->MunicipioId))? $pqr->municipio->departamento->CodigoDaneDepartamento : '',
                            'Region' => (!empty($pqr->MunicipioId))? $pqr->municipio->region : '',
                            'Coordenadas' => (!empty($pqr->ClienteId))? $pqr->cliente->Latitud . ', ' . $pqr->cliente->Longitud : null,
                            'Correo Electronico' => $pqr->CorreoElectronico,
                            'Fecha Del Reporte' => $pqr->FechaApertura,
                            'Estado' => $pqr->Status,
                            'Fecha Limite' => $pqr->FechaMaxima,
                            'Fecha Estimada' => $pqr->FechaEstimada,
                            'Fecha Cierre' => $pqr->FechaCierre,
                            'Tiempo Total Paradas' => $tiempo_total_paradas . ' MINUTOS',
                            'Tiempo Total Indisponibilidad' => $tiempo_total_indisponibilidad - $tiempo_total_paradas . ' MINUTOS',
                            'Observacion' => $pqr->Hechos,
                            'Solucion' => $pqr->Solucion                      
                        );
                    }   
                    
    
                    if (count($datos) == 0) {
                        return redirect()->route('pqrs.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }
    
                    $sheet->fromArray($datos, true, 'A1', true);
     
                });
            })->export('xlsx');

        }else{

        }
    }
}
