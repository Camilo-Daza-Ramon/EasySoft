<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Recaudo;
use App\Facturacion;
use App\Proyecto;
use App\Departamento;
use App\Cliente;

use Excel;
use DB;

use Charts;

class EstadisticasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function facturacion()
    {

        if (Auth::user()->can('facturacion-estadisticas-ver')) {

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();

                    
            $primer_dia = date('Y-m');
            $ultimo_dia = date('Y-m-t',strtotime($primer_dia));        

            $data = Recaudo::selectRaw("CAST(Fecha AS DATE) as fecha, sum(valor) as total")
            ->whereRaw("Fecha between '".$primer_dia."-01 00:00:00' and '".$ultimo_dia." 23:59:59' GROUP BY CAST(Fecha AS DATE)")->orderBy('fecha', 'ASC')->get();

            $labels = array();
            $values = array();

            foreach ($data as $key) {
                $labels[] = $key->fecha;
                $values[] = $key->total;
            }

            $grafica_recaudos_mes = Charts::create('line', 'highcharts')
            ->title('Recaudos Mes')
            ->labels($labels)
            ->values($values)
            ->responsive(true);


            $recuados_suspendidos = $this->suspendidos_recaudos(date('Y-m'));

            //dd($recuados_suspendidos);

            

                /*foreach ($facturacion as $key) {           

                    $datos[$key->municipio]['facturado'] = $key->total;
                    $datos[$key->municipio]['cantidad_fac'] = $key->facturado;
                    $facturado[] = $key->total;

                    foreach ($recaudo as $key2) {
                        if ($key->municipio == $key2->municipio) {                                            
                            
                            $datos[$key->municipio]['recaudado'] = $key2->total;
                            $datos[$key->municipio]['cantidad_rec'] = $key2->recaudado;
                            $recaudado[] = $key2->total;
                            $titulos[] = $key->municipio;
                        }
                    }
                }*/

               

                /*$chart = Charts::multi('bar', 'highcharts')
                ->title('Facturado VS Recaudo')
                ->colors(['#f44336','#2196F3'])
                ->labels($titulos)
                ->dataset('Facturado', $facturado)
                ->dataset('Recaudado', $recaudado)
                ->responsive(true);*/


            return view('adminlte::facturacion.estadisticas', [
                'proyectos' => $proyectos,
                'grafica_recaudos_mes' => $grafica_recaudos_mes, 
                'datos' => $data, 
                'departamentos' => $departamentos,
                'recuados_suspendidos' => $recuados_suspendidos
            ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function recaudos(Request $request){

        if (Auth::user()->can('facturacion-estadisticas-ver')) {
            if ($request->ajax()) {


                $datos_ingresos = null;

                if (!empty($request->proyecto) && !empty($request->fecha_desde)) {
                    $datos_ingresos = DB::select('SELECT * from calcular_ingresos(?,?)', [date('Ym',strtotime($request->fecha_desde)), $request->proyecto]);               

                }
                

                $recaudos = Recaudo::selectRaw("SUM(valor) as total, Municipios.NombreMunicipio as municipio, Municipios.NombreDepartamento as departamento")
                ->join('Clientes', 'ClientesRecaudos.ClienteId', '=', 'Clientes.ClienteId')
                ->join('Municipios', 'Clientes.municipio_id','=', 'Municipios.MunicipioId')
                ->where(function ($query) use($request) {
                    if (!empty($request->fecha_desde)) {
                      $query->whereBetween('ClientesRecaudos.Fecha', [$request->fecha_desde,  $request->fecha_hasta]);
                    }

                    if (!empty($request->proyecto)) {
                        $proyecto = $request->proyecto;
                        $query->whereHas('cliente', function ($query) use ($proyecto){
                            $query->where('Clientes.ProyectoId', $proyecto);
                        });
                    }

                    if (!empty($request->municipio)) {
                        $municipio = $request->municipio;
                        $query->whereHas('cliente', function ($query) use ($municipio){
                            $query->where('Clientes.municipio_id', $municipio);
                        });
                    }

                    if (!empty($request->departamento)) {
                        $departamento = $request->departamento;
                        $query->whereHas('cliente', function ($query) use ($departamento){
                            $query->whereHas('municipio', function ($query) use ($departamento){
                                $query->where('Municipios.DeptId', $departamento);
                            });
                        });
                    }
                    
                })
                ->groupBy(['Municipios.NombreMunicipio', 'Municipios.NombreDepartamento'])
                ->orderBy('Municipios.NombreDepartamento', 'ASC')
                ->orderBy('Municipios.NombreMunicipio', 'ASC')        
                ->get();

                $label = array();
                $dataset = array();

                foreach ($recaudos as $dato) {
                    $label[] = $dato->municipio;
                    $dataset[] = intval($dato->total);                
                }

                $facturado_recaudado = $this->data_facturado_recadudado($request->fecha_desde, $request->fecha_hasta, $request->proyecto, $request->municipio, $request->departamento);

                return response()->json(['labels' => $label, 'data' => $dataset, 'recaudos' => $recaudos, 'facturado_recaudado' => $facturado_recaudado, 'data_ingresos' => $datos_ingresos]);
            }
        }else{
            abort(403);
        }
    }

    private function data_facturado_recadudado($fecha_desde,$fecha_hasta,$proyecto,$municipio,$departamento){

        $recaudo = Recaudo::selectRaw("Municipios.NombreMunicipio as municipio, COUNT(ClientesRecaudos.ClienteId) as cantidad,'RECAUDO' as tipo, SUM(ClientesRecaudos.valor) as total")            
        ->join('Clientes', 'ClientesRecaudos.ClienteId', '=', 'Clientes.ClienteId')
        ->join('ProyectosUbicaciones', 'Clientes.UbicacionId', '=', 'ProyectosUbicaciones.UbicacionId')
        ->join('Municipios', 'ProyectosUbicaciones.MunicipioId', '=', 'Municipios.MunicipioId')
        ->where(function ($query) use($fecha_desde,$fecha_hasta,$proyecto,$municipio,$departamento) {
          if (!empty($fecha_desde)) {
            $query->whereBetween('ClientesRecaudos.Fecha', [$fecha_desde, $fecha_hasta]);
          }

          if (!empty($proyecto)) {
              $query->whereHas('cliente', function ($query) use ($proyecto){
                  $query->where('Clientes.ProyectoId', $proyecto);
              });
          }

          if (!empty($municipio)) {
              $query->whereHas('cliente', function ($query) use ($municipio){
                  $query->where('Clientes.municipio_id', $municipio);
              });
          }

          if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
              $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
              });
            });
          }        
        })
        ->groupBy('Municipios.NombreMunicipio');

        $objeto = Facturacion::selectRaw("Municipios.NombreMunicipio as municipio, COUNT(Facturacion.FacturaId) as cantidad, 'FACTURA' as tipo, SUM(Facturacion.Internet) as total")            
        ->join('Clientes', 'Facturacion.ClienteId', '=', 'Clientes.ClienteId')
        ->join('ProyectosUbicaciones', 'Clientes.UbicacionId', '=', 'ProyectosUbicaciones.UbicacionId')
        ->join('Municipios', 'ProyectosUbicaciones.MunicipioId', '=', 'Municipios.MunicipioId')    
        ->where(function ($query) use($fecha_desde,$proyecto,$municipio,$departamento) {
          if (!empty($fecha_desde)) {
            $query->where('Facturacion.Periodo', date('Ym',strtotime($fecha_desde)));
          }

          if (!empty($proyecto)) {
              /*$query->whereHas('cliente', function ($query) use ($proyecto){
                  $query->where('Clientes.ProyectoId', $proyecto);
              });*/
            
                $query->where('Facturacion.ProyectoId', $proyecto);

          }

          if (!empty($municipio)) {
              $query->whereHas('cliente', function ($query) use ($municipio){
                  $query->where('Clientes.municipio_id', $municipio);
              });
          }

          if (!empty($departamento)) {
            $query->whereHas('cliente', function ($query) use ($departamento){
              $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
              });
            });
          }        
        })
        ->whereNull('Facturacion.estado')
        ->union($recaudo)
        ->groupBy('Municipios.NombreMunicipio')->get();

        $titulos = array();
        $facturado = array();
        $recaudado = array();

        $datos = array();

        foreach ($objeto as $key ) {
          switch ($key->tipo) {
            case 'RECAUDO':
              $datos[$key->municipio]['recaudado'] = intval($key->total);
              $datos[$key->municipio]['cantidad_rec'] = $key->cantidad;
              $recaudado[] = intval($key->total);

              if (!in_array($key->municipio, $titulos)){
                $titulos[] = $key->municipio;
              }

              break;
            
            case 'FACTURA':
              $datos[$key->municipio]['facturado'] = intval($key->total);
              $datos[$key->municipio]['cantidad_fac'] = $key->cantidad;
              $facturado[] = intval($key->total);

              if (!in_array($key->municipio, $titulos)){
                $titulos[] = $key->municipio;
              }

              break;
            }
        }

        return array('titulos' => $titulos, 'facturado' => $facturado, 'recaudado' => $recaudado, 'datos' => $datos);
    }


    public function exportar(Request $request){

        if (Auth::user()->can('estadisticas-exportar')) {
            Excel::create('estadisticas', function($excel) use($request) {
     
                $excel->sheet('Estadisticas', function($sheet) use($request) {

                    $fecha_desde = $request->fecha_desde;
                    $fecha_hasta = $request->fecha_hasta;
                    $proyecto = $request->proyecto;
                    $municipio = $request->municipio;
                    $departamento = $request->departamento;

                    $datos = array();

                    $facturacion = Facturacion::selectRaw('
                        Facturacion.ClienteId, 
                        Clientes.Identificacion, 
                        Municipios.NombreMunicipio as municipio, 
                        NumeroDeProyecto, 
                        Facturacion.periodo, 
                        Facturacion.ValorTotal as total_facturado, 
                        SaldoEnMora as mora, 
                        (Facturacion.ValorTotal - SaldoEnMora) as total_factura,
                        ISNULL(valor,0) as recaudado,
                        ClientesRecaudos.fecha,
                        CASE WHEN (valor - SaldoEnMora) - ISNULL(CASE WHEN ValorTotal < 0 THEN ValorTotal *-1 ELSE 0 END,0) >0 
                            THEN (valor - SaldoEnMora) 
                            ELSE 0 
                        END as recaudo_neto,
                        ISNULL(
                            CASE WHEN valor <= SaldoEnMora 
                                THEN valor 
                                ELSE SaldoEnMora
                            END, 0) as abono_mora,

                        ISNULL(
                            CASE WHEN facturas_electronicas.FacturaId > 0 
                                THEN Facturacion.ValorTotal - SaldoEnMora
                                ELSE 0
                            END, 0) as dian,
                        facturas_electronicas.numero_factura_dian,
                        facturas_electronicas.archivo

                        ')                    
                    ->join('ClientesRecaudos', 
                        function($join){
                            $join->on('Facturacion.ClienteId','ClientesRecaudos.ClienteId')
                            ->whereRaw('Facturacion.Periodo = ClientesRecaudos.Periodo');
                        })
                    ->join('Clientes', 'ClientesRecaudos.ClienteId', 'Clientes.ClienteId')
                    ->join('Municipios', 'Clientes.municipio_id', 'Municipios.MunicipioId')
                    ->join('Proyectos', 'Clientes.ProyectoId', 'Proyectos.ProyectoID')
                    ->leftJoin('facturas_electronicas', 'Facturacion.FacturaId', 'facturas_electronicas.FacturaId')
                    ->where(function ($query) use($fecha_desde,$proyecto,$municipio,$departamento) {
                      if (!empty($fecha_desde)) {
                        $query->where('Facturacion.Periodo', date('Ym',strtotime($fecha_desde)));
                      }

                      if (!empty($proyecto)) {
                          $query->whereHas('cliente', function ($query) use ($proyecto){
                              $query->where('Clientes.ProyectoId', $proyecto);
                          });
                      }

                      if (!empty($municipio)) {
                          $query->whereHas('cliente', function ($query) use ($municipio){
                              $query->where('Clientes.municipio_id', $municipio);
                          });
                      }

                      if (!empty($departamento)) {
                        $query->whereHas('cliente', function ($query) use ($departamento){
                          $query->whereHas('municipio', function ($query) use ($departamento){
                            $query->where('Municipios.DeptId', $departamento);
                          });
                        });
                      }        
                    })
                    ->whereNull('Facturacion.estado')
                    ->get();

                    foreach ($facturacion as $key) {
                        $datos[] = array(
                            'CLIENTE ID' => $key->ClienteId,
                            'CEDULA' => $key->Identificacion,
                            'MUNICIPIO' => $key->municipio,
                            'PROYECTO' => $key->NumeroDeProyecto,
                            'PERIODO' => intval($key->periodo),
                            'TOTAL FACTURADO' => intval($key->total_facturado),
                            'VALOR MORA' => intval($key->mora),
                            'TOTAL FACTURA' => intval($key->total_factura),
                            'TOTAL RECAUDO' => intval($key->recaudado),                            
                            'FECHA PAGO' => $key->fecha,
                            'RECAUDO NETO' => intval($key->recaudo_neto),
                            'ABONO MORA' => intval($key->abono_mora),
                            'TOTAL DIAN' => intval($key->dian),
                            'FACTURA DIAN' => $key->numero_factura_dian,
                            'FACTURA DIAN ARCHIVO' => $key->archivo,
                        );
                    }
                    

                    if (count($facturacion) == 0) {
                        return redirect()->route('estadisticas.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);
                    $sheet->fromArray($datos, true, 'A1', true);

                    //$sheet->fromArray($datos);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }


    private function suspendidos_recaudos($periodo){


        /*SELECT 
            m.NombreMunicipio as municipio,
            COUNT(c.ClienteId) total_clientes, 
            SUM(
                CASE WHEN (f.ValorTotal > 0) THEN
                    f.ValorTotal
                ELSE
                    0
                END
            ) as total_facturado,
            SUM(
                CASE WHEN(fn.tipo_nota = 'CREDITO') THEN
                    fn.valor_total
                ELSE
                    0
                END
            ) AS nota_credito,
            SUM(
                CASE WHEN(fn.tipo_nota = 'DEBITO') THEN
                    fn.valor_total
                ELSE
                    0
                END
            ) AS nota_debito,
            COUNT(cr2.ClienteId) cantidad_recaudos , 
            SUM(cr2.valor) as recaudo_mes,
            SUM(n.reconexiones)
        FROM Clientes as c
        INNER JOIN Municipios as m ON c.municipio_id = m.MunicipioId
        INNER JOIN (
            SELECT n.ClienteId, MAX(CASE WHEN(n.fecha_fin) is null THEN 0 ELSE 1 END) as reconexiones
            FROM novedades as n
            WHERE n.concepto = 'Suspensión por Mora' and n.fecha_inicio > '2024-07-01 00:00:00'
            GROUP BY n.ClienteId
        ) as n ON c.ClienteId = n.ClienteId 

        INNER JOIN Facturacion as f ON n.ClienteId = f.ClienteId and f.Periodo = 202406
        LEFT JOIN facturas_notas as fn ON fn.factura_id = f.FacturaId

        LEFT JOIN (

            SELECT cr.ClienteId, SUM(cr.valor) as valor FROM Clientes as c 
            INNER JOIN ClientesRecaudos as cr on c.ClienteId = cr.ClienteId
            WHERE cr.Fecha BETWEEN '2024-07-01 00:00:00' and '2024-07-31 00:00:00'
            GROUP BY cr.ClienteId) as cr2 ON cr2.ClienteId = c.ClienteId

        WHERE f.estado is null
        GROUP BY m.NombreMunicipio
        ORDER BY m.NombreMunicipio ASC*/

        $datos = Cliente::selectRaw("
            m.MunicipioId,
            m.NombreMunicipio as municipio,
            COUNT(Clientes.ClienteId) total_clientes, 
            SUM(
                CASE WHEN (f.ValorTotal > 0) THEN
                    f.ValorTotal
                ELSE
                    0
                END
            ) as total_facturado, 
            COUNT(cr2.ClienteId) cantidad_recaudos, 
            SUM(cr2.valor) as recaudo_mes,
            SUM(nv.reconexiones) as reconectados
            ")
        ->join('Municipios as m', 'Clientes.municipio_id', '=','m.MunicipioId')
        ->join(DB::raw("(
            SELECT 
                n.ClienteId, 
                MAX(CASE WHEN(n.fecha_fin) is null THEN 0 ELSE 1 END) as reconexiones
            FROM novedades as n
            WHERE n.concepto = 'Suspensión por Mora' and n.fecha_inicio > '$periodo-01 00:00:00'
            GROUP BY n.ClienteId
            ) as nv"), function($join){
            $join->on('Clientes.ClienteId', '=', 'nv.ClienteId');
        })
        ->join('Facturacion as f', function($join) use($periodo){

            $join->on('nv.ClienteId', '=', 'f.ClienteId')->where('f.Periodo', '=', date("Ym",strtotime($periodo."- 1 month")));

        })
        
        ->leftJoin(DB::raw("(SELECT 
                cr.ClienteId, 
                SUM(cr.valor) as valor 
            FROM Clientes as c 
            INNER JOIN ClientesRecaudos as cr on c.ClienteId = cr.ClienteId
            WHERE cr.Fecha BETWEEN '$periodo-01 00:00:00' and '".date("Y-m-t", strtotime($periodo))." 23:59:59'
            GROUP BY cr.ClienteId
        ) as cr2"), function($join){
            $join->on('cr2.ClienteId', '=', 'Clientes.ClienteId');
        })        
        ->whereNull("f.estado")
        ->groupBy("m.MunicipioId")
        ->groupBy("m.NombreMunicipio")        
        ->orderBy("m.NombreMunicipio","ASC")
        ->get();

        return $datos;

    }

    public function filtro_suspendidos_recaudos(Request $request){

        if (Auth::user()->can('facturacion-estadisticas-ver')) {
            if ($request->ajax()) {

                $datos = $this->suspendidos_recaudos($request->mes);

            }

            return response()->json(['datos' => $datos]);

        }else{
            abort(403);
        }


    }
}
