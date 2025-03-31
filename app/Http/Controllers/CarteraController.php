<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\Proyecto;
use App\Recaudo;
use App\Facturacion;

use App\HistorialFacturaPagoV;
use App\Departamento;
use Excel;
use Illuminate\Support\Facades\DB;

class CarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd('entro');
        if (Auth::user()->can('cartera-listar')) {
            $cartera = HistorialFacturaPagoV::select('historial_factura_pagoV.*')
            ->join('Clientes', 'Clientes.ClienteId', 'historial_factura_pagoV.ClienteId')
            ->Cedula($request->get('documento'))
            ->Proyecto($request->get('proyecto'))
            ->Departamento($request->get('departamento'))
            ->Municipio($request->get('municipio'))
            //->where('total_deuda','>', 0)
            //->whereIn('Clientes.ProyectoID', [2])
            ->paginate(15);

            $facturacionPorProyecto = DB::select("
            SELECT ClienteId, ProyectoId, SUM(ValorTotal) as facturacion_total
            FROM Facturacion
            GROUP BY ProyectoId, ClienteId
            ORDER BY ClienteId ASC"
        );
            
            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();
            $departamentos = Departamento::orderBy('NombreDelDepartamento', 'ASC')->get();


            return view('adminlte::facturacion.cartera.index',compact('cartera', 'proyectos', 'facturacionPorProyecto', 'departamentos'));

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

    public function exportar(Request $request) {
        if (Auth::user()->can('cartera-exportar')) {
            Excel::create('cartera', function ($excel) use ($request) {
                $excel->sheet('Cartera', function ($sheet) use ($request) {
                    $datos = array();

                    // Obtener la facturación total del cliente para todos los proyectos
                    $facturas = Facturacion::select(DB::raw('MAX(FacturaId) as ultima_factura, MIN(FacturaId) as primera_factura'), 'ClienteId', 'ProyectoId')
                        ->Proyecto($request->proyecto)
                        ->Municipio($request->municipio)
                        ->groupBy('ProyectoId', 'ClienteId')
                        ->orderBy('ProyectoId', 'ASC')
                        ->orderBy('ClienteId', 'ASC')
                        ->get();

                    if($facturas->count() > 0){

                        foreach ($facturas as $facturacion) {

                            $factura = Facturacion::find($facturacion->ultima_factura);                           
                            $datos[] = $this->crearRegistro($factura, $facturacion->proyecto->NumeroDeProyecto);
                        }

                    }else{
                        return redirect()->route('cartera.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }
    
                    $sheet->fromArray($datos);
                });
            })->export('xlsx');
        } else {
            abort(403);
        }
    }
    private function obtenerPagos($clienteId, $periodo) {
        $pagos = Recaudo::select('valor')
        ->where([['ClienteId', $clienteId],['Periodo', $periodo]])
        ->groupBy('ClienteId')
        ->sum('valor');

        return $pagos;
    }
    // Función para crear un registro con el proyecto especificado
    private function crearRegistro($key, $proyecto) {

        //$meses_mora = $key->ValorTotal / $key->Internet;
        $contrato = $key->cliente->contrato->pluck('estado')->toArray();

        return array(
            'CEDULA' => $key->cliente->Identificacion,
            'NOMBRE' => mb_convert_case($key->cliente->NombreBeneficiario.' '. $key->cliente->Apellidos, MB_CASE_TITLE, "UTF-8"),
            'CELULAR' => $key->cliente->TelefonoDeContactoMovil,
            'CORREO' => $key->cliente->CorreoElectronico,
            'CLASIFICACION' => $key->cliente->Clasificacion,
            'PROYECTO' => $proyecto,
            'DEPARTAMENTO' => $key->cliente->municipio->departamento->NombreDelDepartamento,
            'MUNICIPIO' => $key->cliente->municipio->NombreMunicipio,
            'DIRECCION' => $key->cliente->DireccionDeCorrespondencia,
            'BARRIO' => $key->cliente->Barrio,
            'COORDENADAS' => $key->cliente->Latitud . " , " . $key->cliente->Longitud,
            'ESTRATO' => $key->cliente->Estrato,
            'TARIFA' => intval($key->Internet),
            'ESTADO CLIENTE' => $key->cliente->Status,
            'ESTADO DEL SERVICIO' => $key->cliente->EstadoDelServicio,
            'ESTADO DEL CONTRATO' => implode($contrato),
            'ULTIMA FACTURA' => $key->FacturaId,
            'PERIODO FACTURADO' => strtoupper(strftime("%B %Y",strtotime(substr($key->PeriodoServicio,0,4) .'-'. substr($key->PeriodoServicio,4)))),
            'VALOR FACTURA' => $key->ValorTotal,
            'SALDO EN MORA' => $key->SaldoEnMora,
            
            //'ULTIMA FACTURA' => (isset($key->cliente->ultima_factura))? intval($key->cliente->ultima_factura->factura_id) : '',
            //'PERIODO FACTURADO' => (isset($key->cliente->ultima_factura))? strtoupper(strftime("%B %Y",strtotime(substr($key->cliente->ultima_factura->factura->PeriodoServicio,0,4) .'-'. substr($key->cliente->ultima_factura->factura->PeriodoServicio,4)))) : '',
            //'PERIODO' => (isset($key->cliente->ultima_factura))? intval($key->cliente->ultima_factura->factura->Periodo) : '', 
            //'SALDO EN MORA' => (isset($key->cliente->ultima_factura))? $key->cliente->ultima_factura->factura->SaldoEnMora : '',
            //'MESES MORA' =>  $meses_mora,
            //'TOTAL DEUDA' => (isset($key->cliente->historial_factura_pago))? $key->cliente->historial_factura_pago->total_deuda : '',
            'TOTAL DEUDA HOY' => (isset($key->cliente->historial_factura_pago))? $key->cliente->historial_factura_pago->total_deuda : ''
            //'VALOR TOTAL FACTURADO PROYECTO' => $valorTotalProyecto,
        );      
    }
}    