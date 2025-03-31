<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\ClienteReemplazo;
use App\MetaCliente;
use App\ClienteReemplazoV;
use App\Departamento;
use App\Proyecto;
use App\ClienteContrato;
use App\ContratoServicio;
use App\ContratoArchivo;
use Excel;
use DB;

use App\Custom\PlantillasContratos\AmigoRed;
use App\Custom\Data;
use PDF;
use Carbon\Carbon;
use Storage;

use App\Traits\Contratos;
use App\Traits\DeclaracionesJuramentadas;

class CambiosReemplazosController extends Controller
{
    use Contratos, DeclaracionesJuramentadas;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /*
        SELECT c.Identificacion, mu.NombreMunicipio, mu.NombreDepartamento, m.nombre as 'META', c.Status as 'ESTADO CLIENTE'
        FROM Clientes as c
        INNER JOIN metas_clientes as mc ON mc.ClienteId = c.ClienteId
        INNER JOIN metas as m ON mc.meta_id = m.id
        INNER JOIN Municipios as mu ON c.municipio_id = mu.MunicipioId
        AND  c.Status NOT IN ('ACTIVO')*/
        if (Auth::user()->can('cambios-reemplazos-listar')) {

            /*$cambios_reemplazos = MetaCliente::
                                Buscar($request->get('documento'))
                                ->select('metas_clientes.*')
                                ->join('Clientes', 
                                    function($join){
                                        $join->on('metas_clientes.ClienteId','Clientes.ClienteId')
                                        ->whereNotIn('Clientes.Status',['ACTIVO']);
                                    })
                                ->paginate(15);*/

            $cambios_reemplazos = ClienteReemplazoV::Cedula($request->get('documento'))
                ->Proyecto($request->get('proyecto'))
                ->Municipio($request->get('municipio'))
                ->Estado($request->get('estado'))
                ->Meta($request->get('meta'))
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->paginate(15);
            $metas = ClienteReemplazoV::select('META')
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->groupBy('META')
                ->get();

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->get();

            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

            return view('adminlte::cambios-reemplazos.index', compact('cambios_reemplazos', 'departamentos', 'metas', 'proyectos'));
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
        if (Auth::user()->can('cambios-reemplazos-crear')) {

            $this->validate(request(), [
                'meta_cliente_id' => 'required',
                'contrato_a_id' => 'required',
                'cliente_n_id' => 'required',
                'contrato_n_id' => 'required',
                'fecha' => 'required'
            ]);

            $result = DB::transaction(function () use($request) {

                $contrato_entrante = ClienteContrato::find($request->contrato_n_id);
                $contrato_saliente = ClienteContrato::find($request->contrato_a_id);

                $cliente_reemplazo = new ClienteReemplazo;
                $cliente_reemplazo->meta_cliente_id = $request->meta_cliente_id;
                $cliente_reemplazo->antiguo_cliente_contrato_id = $request->contrato_a_id;
                $cliente_reemplazo->cliente_nuevo_id = $request->cliente_n_id;
                $cliente_reemplazo->fecha_reemplazo = $request->fecha;
                $cliente_reemplazo->observacion = $request->observacion;
                $cliente_reemplazo->cliente_reemplazo_id = $request->reemplazo_id;

                if($contrato_entrante->vigencia_meses <  $contrato_saliente->vigencia_meses){                    
                    $cliente_reemplazo->nuevo_cliente_contrato_id = $request->contrato_n_id;
                }else{

                    $contrato_entrante->fecha_final = $request->fecha;
                    $contrato_entrante->estado = 'FINALIZADO';

                    if($contrato_entrante->save()){

                        $cliente = $contrato_entrante->cliente;

                        $contrato = new ClienteContrato;
                        $contrato->tipo_cobro = $contrato_entrante->tipo_cobro;

                        $date1 = new \DateTime($request->fecha);
                        $date2 = new \DateTime(date('Y-m-d', strtotime($contrato_saliente->fecha_operacion . " + " . $contrato_saliente->vigencia_meses . " month")));

                        $diff = $date1->diff($date2);
                        $vigencia = ($diff->m) + ($diff->y * 12);

                        if($vigencia == 0){
                            $vigencia = 1;
                        }
            
                        $contrato->vigencia_meses = $vigencia + 1;

                        $contrato->fecha_inicio = $request->fecha;
                        $contrato->fecha_instalacion = $request->fecha;
                        $contrato->clausula_permanencia = $contrato_entrante->clausula_permanencia;
                        $contrato->estado = "VIGENTE";
                        $contrato->vendedor_id = Auth::user()->id;
                        $contrato->ClienteId = $contrato_entrante->ClienteId;

                        if ($contrato->save()) {

                            $contrato->referencia = date('Y').'-'.$contrato->id;

                            if(!$contrato->save()){
                                DB::rollBack();
                                return ['error','Error al actualizar el contrato entrante!'];
                            }

                            /* --------------------------------SERVICIO-----------------------------------*/
                            foreach($contrato_entrante->servicio as $servicio_entrante){

                                $servicio = new ContratoServicio;
                                $servicio->nombre = $servicio_entrante->nombre;
                                $servicio->descripcion = $servicio_entrante->descripcion;
                                $servicio->cantidad = $servicio_entrante->cantidad;
                                $servicio->unidad_medida = 'Megas';
                                $servicio->valor = $servicio_entrante->valor;
                                $servicio->tipo_servicio = 'INTERNET';
                                $servicio->iva = $servicio_entrante->iva;                            
                                $servicio->estado = 'Activo';
                                $servicio->contrato_id = $contrato->id;

                                if($servicio->save()){

                                    $servicio_entrante->estado = 'Inactivo';

                                    if($servicio_entrante->save()){
                                        
                                        $cliente_reemplazo->nuevo_cliente_contrato_id = $contrato->id;
                                        
                                    }else{
                                        DB::rollBack();
                                        return ['error','Error al actualizar el servicio entrante!'];
                                    }
                                    
                                }else{
                                    DB::rollBack();
                                    return ['error', 'Error al crear el servicio!'];
                                }
                            }                            

                        }else{
                            DB::rollBack();
                            return ['error', 'Error al crear el contrato!']; 
                        }                    
                        
                    }else{
                        DB::rollBack();
                        return ['error', 'Error al finalizar el contrato'];
                    }
                }

                if ($cliente_reemplazo->save()) {
                    return ['success', 'Reemplazo realizado correctamente'];                                        
                }else{
                    DB::rollBack();
                    return ['error', 'Error al finalizar la novedad'];
                }
            });
            
            return redirect()->route('cambios-reemplazos.index')->with($result[0], $result[1]);
           
        } else {
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
        if (Auth::user()->can('cambios-reemplazos-ver')) {

            $cambio_reemplazo = ClienteReemplazo::findOrFail($id);

            if (Auth::user()->proyectos()->count() > 0) {

                $array = Auth::user()->proyectos()->pluck('ProyectoID')->toArray();

                if (!in_array($cambio_reemplazo->cliente->ProyectoId, $array)) {
                    abort(403);
                }
            }

            return view('adminlte::cambios-reemplazos.show', compact('cambio_reemplazo'));
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
        if (Auth::user()->can('cambios-reemplazos-actualizar')) {
            $cambio_reemplazo = ClienteReemplazo::findOrFail($id);
            return view('adminlte::cambios-reemplazos.edit', compact('cambio_reemplazo'));
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
        if (Auth::user()->can('cambios-reemplazos-actualizar')) {
            $this->validate(request(), [
                'fecha' => 'required'
            ]);

            $cliente_reemplazo = ClienteReemplazo::find($id);
            $cliente_reemplazo->fecha_reemplazo = $request->fecha;
            $cliente_reemplazo->observacion = $request->observacion;

            if ($cliente_reemplazo->save()) {
                return redirect()->route('cambios-reemplazos.show', $id)->with('success', 'Registro actualizado.');
            } else {
                return redirect()->route('cambios-reemplazos.edit', $id)->with('danger', 'Error al actualizar.');
            }
        } else {
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
        if (Auth::user()->can('cambios-reemplazos-eliminar')) {
            $reemplazo = ClienteReemplazo::findOrFail($id);
            if ($reemplazo->delete()) {

                return redirect()->route('cambios-reemplazos.index')->with('success', 'Registro eliminado satisfactoriamente.');
            } else {
                return redirect()->route('cambios-reemplazos.index')->with('error', 'No se pudo eliminar.');
            }
        } else {
            abort(403);
        }
    }

    public function ajax(Request $request)
    {
        if ($request->ajax()) {

            $contrato_saliente = ClienteContrato::find($request->contrato_saliente_id);

            $clientes = [];

            if(!empty($contrato_saliente)){

                $clientes = Cliente::selectRaw("Clientes.ClienteId, Clientes.Identificacion, Clientes.NombreBeneficiario + ' ' + Clientes.Apellidos as nombre, Clientes.municipio_id, Municipios.NombreMunicipio + ' - ' + Municipios.NombreDepartamento as municipio, Clientes.Status")
                ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                ->join('clientes_contratos as cc', function($join) use($contrato_saliente){
                    $join->on('Clientes.ClienteId', '=', 'cc.ClienteId')
                    ->where('cc.estado', '=', 'VIGENTE')
                    ->where(function($query) use($contrato_saliente){
                        $query->where('cc.fecha_instalacion', '<=', $contrato_saliente->fecha_instalacion)
                        ->orWhere('cc.vigencia_meses', '<', $contrato_saliente->vigencia_meses);
                    });
                    // ->where([['cc.estado', '=', 'VIGENTE'], ['cc.fecha_instalacion', '<=', $contrato_saliente->fecha_instalacion]])
                    // ->where([['cc.estado', '=', 'VIGENTE'], ['cc.vigencia_meses', '<', $contrato_saliente->vigencia_meses]]);
                })
                ->leftJoin('metas_clientes', 'Clientes.ClienteId', '=', 'metas_clientes.ClienteId')
                ->leftJoin('clientes_reemplazos', 'Clientes.ClienteId', '=', 'clientes_reemplazos.cliente_nuevo_id')
                ->where([
                    ['municipio_id', $request->municipio_id],
                    ['Clientes.ProyectoId', $request->proyecto_id],
                    ['Clientes.Status', 'ACTIVO']
                ])
                ->whereNull('metas_clientes.ClienteId')
                ->whereNull('clientes_reemplazos.cliente_nuevo_id')
                ->get();
            }

            return response()->json(array("data" => $clientes));
        }
    }

    public function exportar(Request $request)
    {
        if (Auth::user()->can('cambios-reemplazos-exportar')) {

            Excel::create('cambios-reemplazos', function ($excel) use ($request) {

                $excel->sheet('Cambios-Reemplazos', function ($sheet) use ($request) {

                    $datos = "";

                    /*SELECT c.Identificacion, mu.NombreMunicipio, mu.NombreDepartamento, m.nombre as 'META', c.Status as 'ESTADO CLIENTE', cliente_nuevo.Identificacion, cliente_nuevo.nombres, cliente_nuevo.apellidos
                      FROM metas_clientes as mc
                      INNER JOIN Clientes as c ON mc.ClienteId = c.ClienteId
                      INNER JOIN metas as m ON mc.meta_id = m.id
                      INNER JOIN Municipios as mu ON c.municipio_id = mu.MunicipioId
                      LEFT JOIN (SELECT cr.meta_cliente_id, cn.Identificacion, cn.NombreBeneficiario as nombres, cn.Apellidos as apellidos
                                    FROM clientes_reemplazos as cr
                                    INNER JOIN Clientes as cn ON cr.cliente_nuevo_id = cn.ClienteId) as cliente_nuevo ON mc.id = cliente_nuevo.meta_cliente_id
                    WHERE c.Status NOT IN ('ACTIVO')*/
                    /*
                    $datos = ClienteReemplazoV::selectRaw("
                    ue.ClienteId AS 'Id Cuenta',
                    ue.EstadoDelServicio AS 'Estado del servicio',
                    pue.NumeroDeProyecto AS 'Region', 
                    due.CodigoDaneDepartamento AS 'Dane Departamento', 
                    due.NombreDelDepartamento AS 'Departamento', 
                    mue.CodigoDaneMunicipio AS 'Dane Municipio', 
                    mue.NombreMunicipio AS 'Municipio', 
                    ue.Barrio AS 'Barrio',  
                    ue.localidad AS 'Localidad',
                    ue.DireccionDeCorrespondencia AS 'Direccion', 
                    ue.zona AS 'Caracterizacion del Suscriptor', 
                    ue.Estrato AS 'estrato', 
                    ue.Latitud + ', ' + ue.Longitud AS 'Coordenadas Grados-decimales', 
                    ue.TipoDeDocumento AS 'Tipo de identificacion', 
                    ue.Identificacion AS 'Numero de documento', 
                    CASE WHEN ue.tipo_beneficiario = 'Comunidad de Conectividad' 
                    THEN 'SI' ELSE 'NO' END AS 'Comunidad de Conectividad',
                    ue.NombreBeneficiario AS 'Nombres', 
                    ue.Apellidos AS 'Apellidos', 
                    ue.TelefonoDeContactoFijo AS 'Telefono Fijo', 
                    ue.TelefonoDeContactoMovil AS 'Celular', 
                    ue.CorreoElectronico AS 'Correo Electronico',
                    ue.Meta AS 'META',
                    ccue.fecha_operacion AS 'Fecha Inicio Operacion', 
                    ccue.fecha_final AS 'Fecha Fin Operacion',
                    '' AS 'Fecha Presentacion Sabana', 
                    '' AS '# de radicado de presentacion', 
                    '' AS '# de sabana presentada', 
                    '' AS 'Motivo', 
                    '' AS 'Fecha concepto interventoría', 
                    '' AS 'Observaciones',
                    us.ClienteId AS 'Id Cuenta ',
                    us.EstadoDelServicio AS 'Estado del servicio ',
                    pus.NumeroDeProyecto AS 'Region ', 
                    dus.CodigoDaneDepartamento AS 'Dane Departamento ', 
                    dus.NombreDelDepartamento AS 'Departamento ', 
                    mus.CodigoDaneMunicipio AS 'Dane Municipio ', 
                    mus.NombreMunicipio AS 'Municipio ', 
                    us.Barrio AS 'Barrio ',  
                    us.localidad AS 'Localidad ',
                    us.DireccionDeCorrespondencia AS 'Direccion ', 
                    us.zona AS 'Caracterizacion del Suscriptor ', 
                    us.Estrato AS 'estrato ', 
                    us.Latitud + ', ' + us.Longitud AS 'Coordenadas Grados-decimales ', 
                    us.TipoDeDocumento AS 'Tipo de identificacion ', 
                    us.Identificacion AS 'Numero de documento ', 
                    CASE WHEN us.tipo_beneficiario = 'Comunidad de Conectividad' 
                    THEN 'SI' ELSE 'NO' END AS 'Comunidad de Conectividad ',
                    us.NombreBeneficiario AS 'Nombres ', 
                    us.Apellidos AS 'Apellidos ', 
                    us.TelefonoDeContactoFijo AS 'Telefono Fijo ', 
                    us.TelefonoDeContactoMovil AS 'Celular ', 
                    us.CorreoElectronico AS 'Correo Electronico ',
                    us.Meta AS 'META ',
                    pqrus.CUN AS 'PQRS ',
                    ccus.fecha_operacion AS 'Fecha Inicio Operacion ', 
                    ccus.fecha_final AS 'Fecha Fin Operacion ',
                    '' AS 'Fecha Solicitud retiro',
                    '' AS 'Dias sustituciones'             
                    ")
                        ->Cedula($request->get('documento'))
                        ->Proyecto($request->get('proyecto'))
                        ->Municipio($request->get('municipio'))
                        ->Estado($request->get('estado'))
                        ->Meta($request->get('meta'))
                        ->where(function ($query) {
                            if (Auth::user()->proyectos()->count() > 0) {
                                $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                            }
                        })
                        ->leftJoin('clientes_reemplazos AS cr', 'clientes_reemplazosV.id', '=', 'cr.id')
                        //->leftJoin('metas_clientes AS mc', 'cr.meta_cliente_id', '=', 'mc.id')
                        ->leftJoin('Clientes AS us', 'clientes_reemplazosV.ClienteId', '=', 'us.ClienteId')
                        ->leftJoin('Proyectos AS pus', 'us.ProyectoId', '=', 'pus.ProyectoID')
                        ->leftJoin('Municipios AS mus', 'us.municipio_id', '=', 'mus.MunicipioId')
                        ->leftJoin('Departamentos AS dus', 'mus.DeptId', '=', 'dus.DeptId')
                        ->leftJoin('clientes_contratos AS ccus', 'us.ClienteId', '=', 'ccus.ClienteId')
                        ->leftJoin('ClientesPQR AS pqrus', function ($join) {
                            $join->on('us.ClienteId', 'pqrus.ClienteId')
                                ->whereRaw("pqrus.PqrId IN (
                                     SELECT MAX(pqrs.PqrId)
                                     FROM ClientesPQR AS pqrs
                                     WHERE pqrs.ClienteId = us.ClienteId AND pqrs.Prioridad = 4
                                     GROUP BY pqrs.ClienteId
                                 )");
                        })
                        ->leftJoin('Clientes AS ue', 'cr.cliente_nuevo_id', '=', 'ue.ClienteId')
                        ->leftJoin('Proyectos AS pue', 'ue.ProyectoId', '=', 'pue.ProyectoID')
                        ->leftJoin('Municipios AS mue', 'ue.municipio_id', '=', 'mue.MunicipioId')
                        ->leftJoin('Departamentos AS due', 'mue.DeptId', '=', 'due.DeptId')
                        ->leftJoin('clientes_contratos AS ccue', 'ue.ClienteId', '=', 'ccue.ClienteId')
                        ->distinct()
                        ->get();
                        dd($datos);*/
                

                      
                    $datosEntrantes = ClienteReemplazoV::selectRaw("
                        ue.ClienteId AS 'Id Cuenta',
                        ue.EstadoDelServicio AS 'Estado del servicio',
                        pue.NumeroDeProyecto AS 'Region', 
                        due.CodigoDaneDepartamento AS 'Dane Departamento', 
                        due.NombreDelDepartamento AS 'Departamento', 
                        mue.CodigoDaneMunicipio AS 'Dane Municipio', 
                        mue.NombreMunicipio AS 'Municipio', 
                        ue.Barrio AS 'Barrio',  
                        ue.localidad AS 'Localidad',
                        ue.DireccionDeCorrespondencia AS 'Direccion', 
                        ue.zona AS 'Caracterizacion del Suscriptor', 
                        ue.Estrato AS 'Estrato', 
                        ue.Latitud + ', ' + ue.Longitud AS 'Coordenadas Grados-decimales', 
                        ue.TipoDeDocumento AS 'Tipo de identificacion', 
                        ue.Identificacion AS 'Numero de documento', 
                        CASE WHEN ue.tipo_beneficiario = 'Comunidad de Conectividad' 
                        THEN 'SI' ELSE 'NO' END AS 'Comunidad de Conectividad',
                        ue.NombreBeneficiario AS 'Nombres', 
                        ue.Apellidos AS 'Apellidos', 
                        ue.TelefonoDeContactoFijo AS 'Telefono Fijo', 
                        ue.TelefonoDeContactoMovil AS 'Celular', 
                        ue.CorreoElectronico AS 'Correo Electronico',
                        metas_ue.nombre AS 'Meta',
                        ccue.fecha_operacion AS 'Fecha Inicio Operacion', 
                        ccue.fecha_final AS 'Fecha Fin Operacion',
                        '' AS 'FECHA PRESENTACION SABANA', 
                        '' AS '# DE RADICADO DE PRESENTACION', 
                        '' AS '# DE SABANA PRESENTADA', 
                        'Sustitucion' AS 'MOTIVO', 
                        '' AS 'FECHA CONCEPTO INTERVENTORIA', 
                        '' AS 'Radicado concepto interventoria', 
                        '' AS 'OBSERVACIONES'
                        ")
                        ->leftJoin('clientes_reemplazos AS cr', 'clientes_reemplazosV.id', '=', 'cr.id')
                        ->leftJoin('Clientes AS ue', 'cr.cliente_nuevo_id', '=', 'ue.ClienteId')
                        ->leftJoin('Proyectos AS pue', 'ue.ProyectoId', '=', 'pue.ProyectoID')
                        ->leftJoin('Municipios AS mue', 'ue.municipio_id', '=', 'mue.MunicipioId')
                        ->leftJoin('Departamentos AS due', 'mue.DeptId', '=', 'due.DeptId')
                        ->leftJoin('metas_clientes AS mcue', 'cr.meta_cliente_id', '=', 'mcue.id')
                        ->leftJoin('metas AS metas_ue', 'mcue.meta_id', '=', 'metas_ue.id')                      
                        ->leftJoin('clientes_contratos AS ccue', 'cr.nuevo_cliente_contrato_id', '=', 'ccue.id');
                        if (!empty($request->get('documento'))) {
                            $datosEntrantes = $datosEntrantes
                                ->where('clientes_reemplazosV.Identificacion', '=', $request->get('documento'))
                                ->orWhere('clientes_reemplazosV.reemplazado_por', '=', $request->get('documento'));
                        }
                        if (!empty($request->get('municipio'))) {
                            $datosEntrantes = $datosEntrantes
                                ->where('clientes_reemplazosV.municipio_id', '=', $request->get('municipio'));
                        }
                        if (!empty($request->get('estado'))) {
                            if ($request->get('estado') == 'PENDIENTE') {
                                $datosEntrantes = $datosEntrantes->whereNull('clientes_reemplazosV.reemplazado_por');
                            }else{
                                $datosEntrantes = $datosEntrantes->whereNotNull('clientes_reemplazosV.reemplazado_por');
                            }  
                        }
                        if (!empty($request->get('meta'))) {
                            $datosEntrantes = $datosEntrantes
                                ->where('clientes_reemplazosV.META', '=', $request->get('meta'));   
                        }
                        if (!empty($request->get('proyecto'))) {
                            $datosEntrantes = $datosEntrantes
                                ->where('clientes_reemplazosV.ProyectoId', '=', $request->get('proyecto'));   
                        }

                        $datosEntrantes = $datosEntrantes
                        //->distinct()
                        ->get();

                        $datosEntrantes = $datosEntrantes->unique(function ($item) {
                            if (empty($item['Id Cuenta']) || is_null($item['Id Cuenta'])) {
                                return null; 
                            }
                            return $item['Id Cuenta'];
                        });
                    
                    $datosEntrantes = $datosEntrantes->values()->all();

                    $datosSalientes = ClienteReemplazoV::selectRaw("
                        us.ClienteId AS 'Id Cuenta ',
                        us.EstadoDelServicio AS 'Estado del servicio ',
                        pus.NumeroDeProyecto AS 'Region ', 
                        dus.CodigoDaneDepartamento AS 'Dane Departamento ', 
                        dus.NombreDelDepartamento AS 'Departamento ', 
                        mus.CodigoDaneMunicipio AS 'Dane Municipio ', 
                        mus.NombreMunicipio AS 'Municipio ', 
                        us.Barrio AS 'Barrio ',  
                        us.localidad AS 'Localidad ',
                        us.DireccionDeCorrespondencia AS 'Direccion ', 
                        us.zona AS 'Caracterizacion del Suscriptor ', 
                        us.Estrato AS 'Estrato ', 
                        us.Latitud + ', ' + us.Longitud AS 'Coordenadas Grados-decimales ', 
                        us.TipoDeDocumento AS 'Tipo de identificacion ', 
                        us.Identificacion AS 'Numero de documento ', 
                        CASE WHEN us.tipo_beneficiario = 'Comunidad de Conectividad' 
                        THEN 'SI' ELSE 'NO' END AS 'Comunidad de Conectividad ',
                        us.NombreBeneficiario AS 'Nombres ', 
                        us.Apellidos AS 'Apellidos ', 
                        us.TelefonoDeContactoFijo AS 'Telefono Fijo ', 
                        us.TelefonoDeContactoMovil AS 'Celular ', 
                        us.CorreoElectronico AS 'Correo Electronico ',
                        metas_us.nombre AS 'Meta ',
                        ccus.fecha_operacion AS 'Fecha Inicio Operacion ', 
                        ccus.fecha_final AS 'Fecha Fin Operacion ',
                        pqrus.CUN AS 'PQRS ',
                        pqrus.FechaApertura AS 'Fecha Solicitud retiro',
                        '' AS 'Dias sustituciones'       
                        ")
                        ->leftJoin('clientes_reemplazos AS cr', 'clientes_reemplazosV.id', '=', 'cr.id')
                        ->leftJoin('Clientes AS us', 'clientes_reemplazosV.ClienteId', '=', 'us.ClienteId')
                        ->leftJoin('Proyectos AS pus', 'us.ProyectoId', '=', 'pus.ProyectoID')
                        ->leftJoin('Municipios AS mus', 'us.municipio_id', '=', 'mus.MunicipioId')
                        ->leftJoin('Departamentos AS dus', 'mus.DeptId', '=', 'dus.DeptId')
                        ->leftJoin('clientes_contratos AS ccus', 'cr.antiguo_cliente_contrato_id', '=', 'ccus.id')
                        ->leftJoin('metas_clientes AS mcus', 'cr.meta_cliente_id', '=', 'mcus.id')
                        ->leftJoin('metas AS metas_us', 'mcus.meta_id', '=', 'metas_us.id')    
                        ->leftJoin('ClientesPQR AS pqrus', function ($join) {
                            $join->on('us.ClienteId', 'pqrus.ClienteId')
                                ->whereRaw("pqrus.PqrId IN (
                                     SELECT MAX(pqrs.PqrId)
                                     FROM ClientesPQR AS pqrs
                                     WHERE pqrs.ClienteId = us.ClienteId
                                     GROUP BY pqrs.ClienteId
                                 )");
                        });
                        if (!empty($request->get('documento'))) {
                            $datosSalientes = $datosSalientes
                                ->where('clientes_reemplazosV.Identificacion', '=', $request->get('documento'))
                                ->orWhere('clientes_reemplazosV.reemplazado_por', '=', $request->get('documento'));
                        }
                        if (!empty($request->get('municipio'))) {
                            $datosSalientes = $datosSalientes
                                ->where('clientes_reemplazosV.municipio_id', '=', $request->get('municipio'));
                        }
                        if (!empty($request->get('estado'))) {
                            if ($request->get('estado') == 'PENDIENTE') {
                                $datosSalientes = $datosSalientes->whereNull('clientes_reemplazosV.reemplazado_por');
                            }else{
                                $datosSalientes = $datosSalientes->whereNotNull('clientes_reemplazosV.reemplazado_por');
                            }   
                        }
                        if (!empty($request->get('meta'))) {
                            $datosSalientes = $datosSalientes
                                ->where('clientes_reemplazosV.META', '=', $request->get('meta'));   
                        }
                        if (!empty($request->get('proyecto'))) {
                            $datosSalientes = $datosSalientes
                                ->where('clientes_reemplazosV.ProyectoId', '=', $request->get('proyecto'));   
                        }

                    $datosSalientes = $datosSalientes
                    //->distinct()
                    ->get();
                    
                    $datos = [];

                    $maxCount = max(count($datosEntrantes), $datosSalientes->count());
                    
                    for ($i = 0; $i < $maxCount; $i++) {
                        $entrada = isset($datosEntrantes[$i]) && $request->get('estado') != 'PENDIENTE'
                            ? (is_object($datosEntrantes[$i]) 
                                ? $datosEntrantes[$i]->toArray() 
                                : $datosEntrantes[$i]) 
                            : [
                                'Id Cuenta' => null,
                                'Estado del servicio' => null,
                                'Region' => null,
                                'Dane Departamento' => null,
                                'Departamento' => null,
                                'Dane Municipio' => null,
                                'Municipio' => null,
                                'Barrio' => null,
                                'Localidad' => null,
                                'Direccion' => null,
                                'Caracterizacion del Suscriptor' => null,
                                'Estrato' => null,
                                'Coordenadas Grados-decimales' => null,
                                'Tipo de identificacion' => null,
                                'Numero de documento' => null,
                                'Comunidad de Conectividad' => null,
                                'Nombres' => null,
                                'Apellidos' => null,
                                'Telefono Fijo' => null,
                                'Celular' => null,
                                'Correo Electronico' => null,
                                'Meta' => null,
                                'Fecha Inicio Operacion' => null,
                                'Fecha Fin Operacion' => null,
                                'Fecha Presentacion Sabana' => null,
                                '# de radicado de presentacion' => null,
                                '# de sabana presentada' => null,
                                'Motivo' => null,
                                'Fecha concepto interventoría' => null,
                                'Radicado concepto interventoría' => null,
                                'Observaciones' => null
                            ];
                    
                        $salida = isset($datosSalientes[$i]) ? (is_object($datosSalientes[$i]) ? $datosSalientes[$i]->toArray() : $datosSalientes[$i]) : [
                            'Id Cuenta ' => null,
                            'Estado del servicio ' => null,
                            'Region ' => null,
                            'Dane Departamento ' => null,
                            'Departamento ' => null,
                            'Dane Municipio ' => null,
                            'Municipio ' => null,
                            'Barrio ' => null,
                            'Localidad ' => null,
                            'Direccion ' => null,
                            'Caracterizacion del Suscriptor ' => null,
                            'estrato ' => null,
                            'Coordenadas Grados-decimales ' => null,
                            'Tipo de identificacion ' => null,
                            'Numero de documento ' => null,
                            'Comunidad de Conectividad ' => null,
                            'Nombres ' => null,
                            'Apellidos ' => null,
                            'Telefono Fijo ' => null,
                            'Celular ' => null,
                            'Correo Electronico ' => null,
                            'Meta ' => null,
                            'Fecha Inicio Operacion ' => null,
                            'Fecha Fin Operacion ' => null,
                            'PQRS ' => null,
                            'Fecha Solicitud retiro' => null,
                            'Dias sustituciones' => null
                        ];

                        $datos[] = array_merge($entrada, $salida);
                    }
                    
                    if (count($datos) == 0) {
                        return redirect()->route('cambios-reemplazos.index')->with('warning', 'No hay datos.');
                    }
                    $sheet->mergeCells('A1:AE1');
                    $sheet->mergeCells('AF1:BF1');

                    $sheet->setCellValue('A1', 'USUARIO ENTRANTE'); 
                    $sheet->setCellValue('AF1', 'USUARIO SALIENTE');
                    //$sheet->fromArray($datos);
                    $sheet->fromArray($datos, null, 'A2', true);
                });
            })->export('xlsx');
        } else {
            abort(403);
        }
    }

    private function generar_contrato($contrato, $cliente){
        $archivos_contratos = ["contrato"];
    
        if(!empty($cliente->proyecto->acta_juramentada) && $cliente->proyecto->acta_juramentada == 1){
            $archivos_contratos[] = "acta_juramentada";
        }

        //Declaramos una ruta
        $directory = 'contratos/' . $contrato->id;
        $extension = 'pdf';
        $adjuntos = [];

        //Si no existe el directorio, lo creamos
        if (!file_exists($directory)) {
            //Creamos el directorio
            Storage::disk('public')->makeDirectory($directory);
        }

        $data = new Data;
        $data_contrato = $data->contrato($contrato->id);

        foreach ($archivos_contratos as $archivo_contrato) {

            $ruta = $directory.'/'.$archivo_contrato.'.'.$extension;

            $adjuntos[] = $ruta;

            if($archivo_contrato == "contrato"){

                switch ($cliente->ProyectoId) {
                    case 6:
                        $this->lp015('F', $data_contrato, Storage::disk('public')->path($ruta));
                        break;
                    case 8:
                        $this->lp018('F', $data_contrato, Storage::disk('public')->path($ruta));
                        break;                            
                    default:
                        $this->amigored('F', $data_contrato, Storage::disk('public')->path($ruta));
                        break;
                }

            }else if($archivo_contrato == "acta_juramentada"){

                switch ($cliente->ProyectoId) {
                    case 6:
                        $this->declaracion_lp15('F', $data_contrato, Storage::disk('public')->path($ruta));
                        break;
                    case 8:
                        $this->declaracion_lp18('F', $data_contrato, Storage::disk('public')->path($ruta));
                        break;                            
                    default:
                        $this->declaracion_findeter('F', $data_contrato, Storage::disk('public')->path($ruta));        
                        break;
                }
            }

            $existe = Storage::disk('public')->exists($ruta);

            if($existe){

                $archivo = new ContratoArchivo;
                $archivo->nombre = $archivo_contrato;
                $archivo->archivo = $ruta;
                $archivo->tipo_archivo = $extension;
                $archivo->estado = 'APROBADO';
                $archivo->contrato_id = $contrato->id;

                if (!$archivo->save()){
                    DB::rollBack();
                    Storage::disk('public')->deleteDirectory($directory);
                    return ['error', 'Error al guardar el registro del archivo del contrato.'];
                }

            }else{
                DB::rollBack();
                Storage::disk('public')->deleteDirectory($directory);
                return ['error', 'Error al generar el '. $archivo_contrato];
            }
        }
    }

}
