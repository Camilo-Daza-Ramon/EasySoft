<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Proyecto;
use App\ProyectoMunicipio;
use App\Cliente;
use App\Municipio;
use App\Instalacion;
use App\Departamento;
use App\Meta;
use App\PlanComercial;
use App\ProyectoClausula;
use App\ProyectoTipoBeneficiario;
use App\ProyectoDocumentacion;
use App\ProyectoPregunta;

use Charts;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->can('proyectos-listar')) {

            $proyectos = Proyecto::with('municipio')
                        ->Nombre($request->get('nombre'))
                        ->Contrato($request->get('contrato'))
                        ->Estado($request->get('estado'))
                        ->where(function ($query) {

                            if(Auth::user()->proyectos()->count() > 0){
                                $query->whereIn('ProyectoID', Auth::user()->proyectos()->pluck('ProyectoID'));
                            }
                        })
                        ->paginate(15);

            $proyecto = new Proyecto;
            $tipos_facturacion = ['VENCIDO','ANTICIPADO', 'NO APLICA'];
            $estados = array('estado' => array('sigla' => 'A', 'descripcion' => 'ACTIVO'), array('sigla' => 'I', 'descripcion' => 'INACTIVO'));

            return view('adminlte::proyectos.index', compact('proyectos','proyecto','tipos_facturacion', 'estados'));
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
        
        if (Auth::user()->can('proyectos-crear')) {

            $this->validate(request(),[
                'proyecto' => 'required',
                'descripcion' => 'required',
                'estado' => 'required',
                'vigencia' => 'required',
                'tipo_facturacion' => 'required',
                'dia_corte_facturacion' => 'required',                
                'limite_meses_mora' => 'required',
                'porcentaje_interes_mora' => 'required'
            ]);

            $consecutivo = Proyecto::select('ProyectoID')->get()->last();

            $proyecto = new Proyecto;
            $proyecto->ProyectoID = (intval($consecutivo->ProyectoID) + 1);
            $proyecto->Entidad = 'SISTECO S.A.S';            
            $proyecto->NumeroDeProyecto = $request->proyecto;
            $proyecto->DescripcionProyecto = $request->descripcion;
            $proyecto->Status = $request->estado;
            $proyecto->Descripcion = $request->descripcion;
            $proyecto->NumeroDeContrato = $request->contrato;
            $proyecto->EmpresaId = 1;
            $proyecto->vigencia = $request->vigencia;
            $proyecto->tipo_facturacion = $request->tipo_facturacion;
            $proyecto->dia_corte_facturacion = $request->dia_corte_facturacion;            
            $proyecto->limite_meses_mora = $request->limite_meses_mora;
            $proyecto->porcentaje_interes_mora = $request->porcentaje_interes_mora;
            $proyecto->condiciones_plan = $request->condiciones_plan;
            $proyecto->condiciones_servicio = $request->condiciones_servicio;
            $proyecto->fecha_fin_proyecto = $request->fecha_fin_proyecto;

            $clausula = $request->clausula_permanencia;

            if ($clausula == 'on') {
                $proyecto->clausula_permanencia= true;
            }else{
                $proyecto->clausula_permanencia = false;
            }

            if ($request->acta_juramentada == 'on') {
                $proyecto->acta_juramentada= true;
            }else{
                $proyecto->acta_juramentada = false;
            }


            if ($proyecto->save()) {
                return redirect()->route('proyectos.index')->with('success','Proyecto agregado correctamente!');
            }else{
                return redirect()->route('proyectos.index')->with('error','Error al crear el proyecto!');
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
    public function show(Request $request, $id)
    {

        if (Auth::user()->can('proyectos-ver')) {

            $proyecto = Proyecto::findOrFail($id);
            $estados_metas = array('FINALIZADA', 'EN EJECUCION');
            $departamentos = Departamento::select('DeptId', 'NombreDelDepartamento')->get();
            $metas = Meta::select('id','nombre')->where('ProyectoID', $id)->get();
            $conceptos_costos = ["Reconexión", "Traslado", "Metro Fibra Adicional", "Instalacion", "Reposición de Equipo"];
            $clausulas = ProyectoClausula::select('numero_mes')->where('proyecto_id', $id)->get();

            $clausulas = $clausulas->toArray();

            $alertas = [];

            if (is_array($proyecto->facturacion_api) || $proyecto->facturacion_api instanceof Countable) {
                if (count($proyecto->facturacion_api) == 0) {
                    $alertas[] = 'Debe especificar el <b>API para facturación electronica.</b>';
                }
            }
            
            if (is_array($proyecto->plan_comercial) || $proyecto->plan_comercial instanceof Countable) {
                if (count($proyecto->plan_comercial) == 0) {
                    $alertas[] = 'Debe Ingresar los <b>planes comerciales</b> que aplican al proyecto.</li>';
                }
            }
            
            if (is_array($proyecto->clausula) || $proyecto->clausula instanceof Countable) {
                if (count($proyecto->clausula) == 0 || count($proyecto->clausula) < $proyecto->vigencia) {
                    $alertas[] = 'Debe ingresar las <b>clausulas de permanencia</b>.</li>';
                }
            }
            
            if (is_array($proyecto->proyecto_municipio) || $proyecto->proyecto_municipio instanceof Countable) {
                if (count($proyecto->proyecto_municipio) == 0) {
                    $alertas[] = 'Debe ingresar los <b>Municipios</b> que se aplican al proyecto.</li>';
                }
            }
            
            if (is_array($proyecto->costo) || $proyecto->costo instanceof Countable) {
                if (count($proyecto->costo) == 0) {
                    $alertas[] = 'Debe ingresar los <b>Costos</b> que se aplican al proyecto.</li>';
                }
            }

            $proyectos = Proyecto::get();
            $estratos = array(1,2,3,'GENERAL');
            $estados = array(
                array('nombre' => 'ACTIVO', 'valor' => 'A'), 
                array('nombre' => 'INACTIVO', 'valor' => 'I')
            );
          
            $tipos_planes = array('GENERAL', 'TARIFA SOCIAL', 'EMPRESARIAL');
            $planes_comerciales = PlanComercial::where('ProyectoId', $id)->get();             

            $documentales = $proyecto->documental()->orderBy('nombre', 'asc')->get();
            $carpetas = $proyecto->carpetas()->get();
            $documental_lista = collect()->merge($carpetas)->merge($documentales);

            $tipos = ['VERSION','MENSUAL','CARPETA'];

            $tipos_preguntas = [
                ['tipo' => 'date', 'descripcion' => 'FECHA'],
                ['tipo' => 'datetime', 'descripcion' => 'FECHA Y HORA'],
                ['tipo' => 'text', 'descripcion' => 'RESPUESTA CORTA'],
                ['tipo' => 'textarea', 'descripcion' => 'RESPUESTA LARGA'],                
                ['tipo' => 'number', 'descripcion' => 'RESPUESTA NUMERICA'],                
                ['tipo' => 'select', 'descripcion' => 'SELECCION UNICA RESPUESTA'],
                ['tipo' => 'check', 'descripcion' => 'SELECCION MULTIPLE RESPUESTA']
            ];

            /*$proyectos_municipios = Municipio::selectRaw('Municipios.MunicipioId, NombreMunicipio,NombreDepartamento,region,COUNT(Clientes.Identificacion) as total_clientes')
            ->join('proyectos_municipios','Municipios.MunicipioId', 'proyectos_municipios.municipio_id')
            ->join('Clientes', function($join) use ($id) {
                $join->on('Municipios.MunicipioId','=','Clientes.municipio_id')
                ->where('Clientes.ProyectoId', '=', $id);
            })
            ->where('proyectos_municipios.proyecto_id',$id)
            ->groupBy(['Municipios.MunicipioId','NombreMunicipio','NombreDepartamento','region'])
            ->get();*/

            $proyectos_municipios = DB::select('SELECT *
                FROM (SELECT m.MunicipioId, m.NombreMunicipio as municipio, m.NombreDepartamento as departamento, c.Status as estado
                FROM Clientes as c
                    INNER JOIN Municipios AS m ON c.municipio_id = m.MunicipioId
                    WHERE c.ProyectoId = ?) as tabla
                PIVOT ( COUNT(estado)
                FOR estado IN ([ACTIVO],[EN INSTALACION],[PENDIENTE],[RECHAZADO],[INACTIVO])) pvt', [$id]);

            
            return view('adminlte::proyectos.show',[
                'proyecto' => $proyecto,
                'alertas' => $alertas,
                'proyectos_municipios' => $proyectos_municipios,
                'proyectos' => $proyectos,
                'estratos' => $estratos,
                'estados' => $estados,
                'tipos_planes' => $tipos_planes, 
                'estados_metas' => $estados_metas, 
                'departamentos' => $departamentos,
                'metas' => $metas,
                'conceptos_costos' => $conceptos_costos,
                'planes_comerciales' => $planes_comerciales,
                'clausulas' => $clausulas,
                'tipos_preguntas' => $tipos_preguntas,
                'documental_lista' => $documental_lista,
                'tipos' => $tipos
            ]);

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
        if (Auth::user()->can('proyectos-editar')) {
            $proyecto = Proyecto::findOrFail($id);
            $tipos_facturacion = ['VENCIDO','ANTICIPADO', 'NO APLICA'];
            $estados = array('estado' => array('sigla' => 'A', 'descripcion' => 'ACTIVO'), array('sigla' => 'I', 'descripcion' => 'INACTIVO'));
            return view('adminlte::proyectos.edit', compact('proyecto','tipos_facturacion','estados'));

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
        if (Auth::user()->can('proyectos-editar')) {

            $this->validate(request(),[
                'proyecto' => 'required',
                'descripcion' => 'required',
                'estado' => 'required',
                'vigencia' => 'required',
                'tipo_facturacion' => 'required',
                'dia_corte_facturacion' => 'required',                
                'limite_meses_mora' => 'required',
                'porcentaje_interes_mora' => 'required'
            ]);

            $proyecto = Proyecto::find($id);
            
            $proyecto->NumeroDeProyecto = $request->proyecto;
            $proyecto->DescripcionProyecto = $request->descripcion;
            $proyecto->Status = $request->estado;
            $proyecto->Descripcion = $request->descripcion;
            $proyecto->NumeroDeContrato = $request->contrato;
            $proyecto->vigencia = $request->vigencia;
            $proyecto->tipo_facturacion = $request->tipo_facturacion;
            $proyecto->dia_corte_facturacion = $request->dia_corte_facturacion;            
            $proyecto->limite_meses_mora = $request->limite_meses_mora;
            $proyecto->porcentaje_interes_mora = $request->porcentaje_interes_mora;
            $proyecto->condiciones_plan = $request->condiciones_plan;
            $proyecto->condiciones_servicio = $request->condiciones_servicio;
            $proyecto->fecha_fin_proyecto = $request->fecha_fin_proyecto;

            $clausula = $request->clausula_permanencia;

            if ($clausula == 'on') {
                $proyecto->clausula_permanencia= true;
            }else{
                $proyecto->clausula_permanencia = false;
            }

            if ($request->acta_juramentada == 'on') {
                $proyecto->acta_juramentada= true;
            }else{
                $proyecto->acta_juramentada = false;
            }


            if ($proyecto->save()) {
                return redirect()->route('proyectos.show', $id)->with('success','Proyecto actualizado correctamente!');
            }else{
                return redirect()->route('proyectos.show', $id)->with('error','Error al actualizar el proyecto!');
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
        if (Auth::user()->can('proyectos-eliminar')) {

            $proyecto = Proyecto::findOrFail($id);

            if (count($proyecto->cliente) == 0) {

                $municipios = $proyecto->municipio;
                if (count($municipios) > 0) {
                    $municipios->delete();
                }                


                $puntos_atencion = $proyecto->punto_atencion;
                if (count($puntos_atencion) > 0) {
                    $puntos_atencion->delete();
                }


                if (count($proyecto->facturacion_api) > 0) {
                    $proyecto->facturacion_api->delete();
                }                

                $metas = $proyecto->meta;
                if (count($metas) > 0) {
                    $metas->delete();
                }


                if($proyecto->delete()){
                    return redirect()->route('proyectos.index')->with('success', 'Se eliminó correctamente.');
                }else{
                    return redirect()->route('proyectos.index')->with('error', 'Error al eliminar');
                }
            }


        }else{

        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function estadisticas($id)
    {

        if (Auth::user()->can('proyectos-estadisticas')) {
            $proyecto = Proyecto::with('municipio')->findOrFail($id);

            /*$proyectos_municipios = Municipio::selectRaw('Municipios.MunicipioId, NombreMunicipio,NombreDepartamento,region,COUNT(Clientes.Identificacion) as total_clientes')
            ->join('proyectos_municipios','Municipios.MunicipioId', 'proyectos_municipios.municipio_id')
            ->join('Clientes', function($join) use ($id) {
                $join->on('Municipios.MunicipioId','=','Clientes.municipio_id')
                ->where('Clientes.ProyectoId', '=', $id);
            })
            ->where('proyectos_municipios.proyecto_id',$id)
            ->groupBy(['Municipios.MunicipioId','NombreMunicipio','NombreDepartamento','region'])
            ->get();*/

            $proyectos_municipios = DB::select('SELECT *
            FROM (SELECT m.MunicipioId, m.NombreMunicipio as municipio, m.NombreDepartamento as departamento, c.Status as estado
                FROM Clientes as c
                    INNER JOIN Municipios AS m ON c.municipio_id = m.MunicipioId
                    WHERE c.ProyectoId = ?) as tabla
                PIVOT ( COUNT(estado)
                FOR estado IN ([ACTIVO],[EN INSTALACION],[PENDIENTE],[RECHAZADO],[INACTIVO])) pvt', [$id]);

            $total_reportados_activo = Cliente::selectRaw("m.NombreMunicipio as municipio, COUNT(CASE WHEN (mc.ClienteId IS NOT NULL) then 1 END) AS reportados , COUNT( CASE WHEN (Clientes.Status = 'ACTIVO') THEN 1 END) AS clientes_activos")
            ->join("Municipios as m", "Clientes.municipio_id","=","m.MunicipioId")
            ->leftJoin("metas_clientes as mc", "Clientes.ClienteId", "=", "mc.ClienteId")
            ->where("Clientes.ProyectoId", $id)
            ->groupBy("m.NombreMunicipio")
            ->orderBy("m.NombreMunicipio")
            ->get();

            /*

            SELECT m.MunicipioId as id, m.NombreMunicipio as municipio, m.NombreDepartamento as departamento, mt.nombre as meta,pmm.total_accesos as total_accesos_meta, SUM(CASE WHEN (c.Status = 'ACTIVO') THEN 1 else 0 END) as total_activos,SUM(CASE WHEN (c2.ClienteId is not null) then 1 else 0 END) AS total_reemplazos_activos
            FROM Clientes as c
            INNER JOIN Municipios as m ON c.municipio_id = m.MunicipioId
            INNER JOIN metas_clientes as mc ON c.ClienteId = mc.ClienteId
            INNER JOIN metas as mt ON mc.meta_id = mt.id
            INNER JOIN proyectos_municipios as pm ON pm.municipio_id = m.MunicipioId and pm.proyecto_id = @id
            LEFT JOIN proyectos_municipios_metas as pmm ON  mc.meta_id = pmm.meta_id and pm.id = pmm.proyecto_municipio_id
            LEFT JOIN clientes_reemplazos as cr ON cr.meta_cliente_id = mc.id
            LEFT JOIN Clientes as c2 ON cr.cliente_nuevo_id = c2.ClienteId and c2.Status = 'ACTIVO'
            WHERE c.ProyectoId = @id
            GROUP BY m.MunicipioId,mt.nombre, m.NombreMunicipio, m.NombreDepartamento,pmm.total_accesos


            */

            $avance_proyecto = DB::select('SELECT * from avance_proyecto(?) order by municipio asc', [$id]);

            $municipios_meta = array();
            

            
            if(count($avance_proyecto) > 0){
                foreach ($avance_proyecto as $avance) {                        

                    $index = false;

                    $index = array_search($avance->municipio, array_column($municipios_meta, 'municipio'));

                    if (!($index === false)) {

                        $municipios_meta[$index]['total_municipio'] = $municipios_meta[$index]['total_municipio'] + $avance->total_activos + $avance->total_reemplazos_activos;
                    }

                    $datos = array();
                    $datos['id'] = $avance->id;
                    $datos['municipio'] = $avance->municipio;
                    $datos['departamento'] = $avance->departamento;
                    $datos['meta'] = $avance->meta;
                    $datos['total_meta'] = $avance->total_accesos_meta;
                    $datos['total_activos_meta'] = $avance->total_activos + $avance->total_reemplazos_activos;
                    $datos['total_municipio'] = $avance->total_activos + $avance->total_reemplazos_activos;

                    $municipios_meta[] = $datos;
                }
            }

            

            $data = array();

            foreach ($total_reportados_activo as $dato) {
                $array = array();
                $array["municipio"] = $dato->municipio;
                $array["reportados"] = $dato->reportados;
                $array["clientes_activos"] = $dato->clientes_activos;
                $data[] = $array;
            }


            $label = array();
            $array_activos = array();
            $array_en_instalacion = array();
            $array_pendiente = array();
            $array_rechazado = array();
            $array_inactivo = array();

            foreach ($proyectos_municipios as $dato) {
              $label[] = $dato->municipio;

              $array_activos[] = intval($dato->ACTIVO);
              $array_en_instalacion[] = intval($dato->{'EN INSTALACION'});
              $array_pendiente[] = intval($dato->PENDIENTE);
              $array_rechazado[] = intval($dato->RECHAZADO);
              $array_inactivo[] = intval($dato->INACTIVO);
            }

            $data_activos = array('name' => 'ACTIVO', 'color' => '#069169', 'data' => $array_activos);
            $data_en_instalacion = array('name' => 'EN INSTALACION', 'color' => '#6699FF', 'data' => $array_en_instalacion);
            $data_pendiente = array('name' => 'PENDIENTE', 'color' => '#FFAB00', 'data' => $array_pendiente);
            $data_rechazado = array('name' => 'RECHAZADO', 'color' => '#F42F63', 'data' => $array_rechazado);
            $data_inactivo = array('name' => 'INACTIVO', 'color' => '#797979', 'data' => $array_inactivo);

            $data_label = json_encode($label);
            $data_ventas_totales = json_encode(array($data_activos,$data_en_instalacion,$data_pendiente,$data_rechazado,$data_inactivo));

            //dd($data);



            $total_ventas_asesor = Cliente::selectRaw('user_id, count(ClienteId) as cantidad')->where('ProyectoId', $id)->groupBy('user_id')->orderBy('cantidad', 'DESC')->get();

            $clientes = Cliente::selectRaw('Clientes.ClienteId, Municipios.NombreMunicipio, Clientes.EstadoDelServicio, Clientes.Status')
            ->join('Municipios', 'Clientes.municipio_id', 'Municipios.MunicipioId')
            ->where('Clientes.ProyectoId', $id)->get();

            $instalaciones = Instalacion::selectRaw('instalaciones.id, Municipios.NombreMunicipio,  instalaciones.estado')
            ->join('Clientes', 'instalaciones.ClienteId', 'Clientes.ClienteId')
            ->join('Municipios', 'Clientes.municipio_id', 'Municipios.MunicipioId')
            ->where('Clientes.ProyectoId', $id)->get();

            $instalaciones_grupo = DB::select('SELECT *
            FROM (SELECT m.MunicipioId ,m.NombreMunicipio as municipio, estado
              FROM instalaciones
              INNER JOIN Clientes as c ON instalaciones.ClienteId = c.ClienteId
              INNER JOIN Municipios  AS m ON c.municipio_id = m.MunicipioId
              WHERE c.ProyectoId = ?) as tabla
            PIVOT ( COUNT(estado)
                FOR estado IN ([APROBADO],[PENDIENTE],[RECHAZADO])) pvt ', [$id]);

            $total_clientes_activos = Cliente::where([['Status', 'ACTIVO'],['ProyectoId', $id]])->get()->count();
            $total_clientes_inactivos = Cliente::where([['Status', 'INACTIVO'],['ProyectoId', $id]])->get()->count();
            $total_reportados = Cliente::where([['Status', 'ACTIVO'],['ProyectoId', $id], ['reporte', 'GENERADO']])->get()->count();
            $total_suspendidos = Cliente::where([
                ['Status', 'ACTIVO'],
                ['EstadoDelServicio', 'Suspendido'],
                ['ProyectoId', $id]
            ])->get()->count();

                
            $grafica_municipio_instalaciones = Charts::database($instalaciones, 'bar', 'highcharts')
                ->title('Total Instalaciones por Municipio')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('NombreMunicipio');


            $grafica_estado_clientes = Charts::database($clientes, 'pie', 'highcharts')
                ->title('Estado de Clientes')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('Status');

            $grafica_estado_servicio_clientes = Charts::database($clientes, 'pie', 'highcharts')
                ->title('Estado del Servicio de Clientes')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('EstadoDelServicio');


            $departamentos = ProyectoMunicipio::select('Municipios.NombreDepartamento', 'Municipios.DeptId')
            ->join('Municipios', 'proyectos_municipios.municipio_id', '=', 'Municipios.MunicipioId')
            ->where('proyectos_municipios.proyecto_id', $id)
            ->groupBy(['Municipios.NombreDepartamento', 'Municipios.DeptId'])
            ->get();

            return view('adminlte::proyectos.estadisticas', compact('proyecto', 'total_clientes_activos', 'total_clientes_inactivos','total_reportados', 'grafica_estado_servicio_clientes', 'grafica_estado_clientes', 'total_ventas_asesor', 'grafica_municipio_instalaciones','proyectos_municipios','instalaciones_grupo','data_ventas_totales','data_label', 'departamentos', 'data', 'municipios_meta','total_suspendidos'));
        }else{
            abort(403);
        }
    }


    public function mapa(Request $request,$id){

        if (Auth::user()->can('proyectos-ver')) {

            if ($request->ajax()) {

                $clientes = Cliente::select('ClienteId','Identificacion','Latitud','Longitud', 'Status', 'DireccionDeCorrespondencia')
                ->where([['ProyectoId',$id], ['municipio_id', $request->municipio]])
                ->get();

                $graficar = array();

                foreach ($clientes as $dato) {
                        $graficar[] = array(
                            'id' => $dato->ClienteId,
                            'titulo' => $dato->Identificacion,                    
                            'latitud' => $dato->Latitud,
                            'longitud' => $dato->Longitud,
                            'estado' => $dato->Status,
                            'direccion' => $dato->DireccionDeCorrespondencia
                        );            
                }

                

                return response()->json(['graficar' => $graficar]);

                //return view('adminlte::proyectos.mapa', ['graficar' => json_encode($graficar)]);
            }
        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){
      if ($request->ajax()) {

        $proyecto = Proyecto::with('costo', 'clausula')->find($request->proyecto_id);

        $departamentos = ProyectoMunicipio::select('d.DeptId as id', 'd.NombreDelDepartamento as nombre')
        ->join('Municipios as m', function($join) use ($request) {
            $join->on('proyectos_municipios.municipio_id','=','m.MunicipioId')
            ->where('proyectos_municipios.proyecto_id', $request->proyecto_id);
        })
        ->join('Departamentos as d', 'm.DeptId', '=', 'd.DeptId')
        ->groupBy(['d.NombreDelDepartamento', 'd.DeptId'])
        ->get();

        $tipos_beneficiarios = ProyectoTipoBeneficiario::where([
            ['proyecto_id', $request->proyecto_id], 
            ['estado', 'ACTIVO']
        ])->get();

        $documentacion = ProyectoDocumentacion::where([
            ['proyecto_id', $request->proyecto_id], 
            ['estado', 'ACTIVO']
        ])->get();

        $preguntas = ProyectoPregunta::where([
            ['proyecto_id', $request->proyecto_id], 
            ['estado', 'ACTIVA']
        ])->get();

        return response()->json([
            'proyecto' => $proyecto, 
            'departamentos' => $departamentos, 
            'tipos_beneficiarios' => $tipos_beneficiarios,
            'documentacion' => $documentacion,
            'preguntas' => $preguntas
        ]);

        /*$ventas = Cliente::selectRaw("COUNT(ClienteId) as total_ventas, Fecha")
        ->where(function ($query) use($request) {
            if (!empty($request->mes)) {
              $query->whereBetween('Fecha', [$request->mes. '-01',  date('Y-m-t',strtotime($request->mes))]);
            }

            if (!empty($request->municipio)) {
              $query->where('municipio_id', $request->municipio);
            }

            if (Auth::user()->hasRole('vendedor')) {
              $query->where('user_id', Auth::user()->id);
            }

            if (!empty($request->proyecto)) {
              $query->where('ProyectoId' , $request->proyecto);
            }
        })        
        ->groupBy('Fecha')
        ->orderBy('Fecha', 'ASC')
        ->get();

        $label = array();
        $dataset_ventas = array();

        foreach ($ventas as $dato) {
            $label[] = $dato->Fecha;
            $dataset_ventas[] = intval($dato->total_ventas);                
        }

        return response()->json(['labels' => $label, 'ventas' => $dataset_ventas]);*/
      }
    }
}
