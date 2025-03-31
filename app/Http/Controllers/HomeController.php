<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cliente;
use App\Instalacion;
use App\Ticket;
use App\HistorialFacturaPagoV;
use App\ProyectoMunicipio;
use App\Mantenimiento;
use App\Solicitud;
use App\PQR;
use App\ReporteOntFallida;
use Charts;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->hasRole('asesor-punto-atencion')) {
            return  redirect()->route('atencion-clientes.index');
        }

        $parametros = [];

        if (!empty($request->get('cedula'))) {

            $parametros['cliente'] = Cliente::palabra($request->get('cedula')) //Cliente::Cedula($caracter)
                ->where(function ($query) {
                    if (Auth::user()->proyectos()->count() > 0) {
                        $query->whereIn('ProyectoId', Auth::user()->proyectos()->pluck('ProyectoID'));
                    }
                })
                ->get();
        }

        if (Auth::user()->can('dashboard-vendedor')) {

            $parametros['clientes'] = Cliente::selectRaw('Status,count(Status) as cantidad')->groupBy('Status')->where('user_id', Auth::user()->id)->get();

            $parametros['departamentos'] = ProyectoMunicipio::select('Municipios.NombreDepartamento', 'Municipios.DeptId')
                ->join('Municipios', 'proyectos_municipios.municipio_id', '=', 'Municipios.MunicipioId')
                ->where('proyectos_municipios.proyecto_id', 6)
                ->groupBy(['Municipios.NombreDepartamento', 'Municipios.DeptId'])
                ->get();
        }

        if (Auth::user()->can('dashboard-tecnico')) {
            $primer_dia = date('Y-m');
            $ultimo_dia = date('Y-m-t', strtotime($primer_dia));

            $parametros['instalaciones'] = Instalacion::selectRaw('estado, count(estado) as cantidad')->groupBy('estado')->where('user_id', Auth::user()->id)->get();

            $instalaciones_fecha = Instalacion::selectRaw('id, fecha')->where('user_id', Auth::user()->id)->whereBetween('fecha', [$primer_dia . '-01', $ultimo_dia])->orderBy('fecha', 'ASC')->get();

            $parametros['grafica_fecha_instalaciones'] = Charts::database($instalaciones_fecha, 'line', 'highcharts')
                ->title('Total instalaciones en el mes')
                ->elementLabel("Total")
                ->responsive(true)
                ->groupBy('fecha');
        }

        if (Auth::user()->can(['dashboard-noc', 'dashboard-admin'])) {
            $parametros['tickets_abiertos'] = Ticket::whereNotIn('EstadoDeTicket', array(0))->count();

            $parametros['reportes_onts_fallidas'] = ReporteOntFallida::count();
        }

        if (Auth::user()->can('dashboard-comercial')) {
            $parametros['clientes_auditar'] = Cliente::where('Status', 'PENDIENTE')->count();
        }

        if (Auth::user()->can('dashboard-admin')) {
            $parametros['solicitudes'] = Solicitud::whereIn('estado', ['PENDIENTE', 'VENCIDA'])->count();

            $parametros['clientes_activos'] = Cliente::select('ClienteId')->where('Status', 'ACTIVO')->count();

            $parametros['clientes_suspendidos'] = Cliente::select('ClienteId')->where('EstadoDelServicio', 'Suspendido')->count();

            $parametros['mantenimientos_pendiente'] = Mantenimiento::select('MantId')->where('Estado', 'ABIERTO')->count();

            $parametros['pqrs_pendientes'] = PQR::select('PqrId')->where('Status', 'ABIERTO')->count();

            $parametros['instalaciones_pendientes'] = Cliente::select('ClienteId')->where('Status', 'EN INSTALACION')->count();
        }

        if (Auth::user()->can(['dashboard-noc', 'dashboard-comercial', 'dashboard-admin'])) {
            
            $parametros['reactivaciones'] = HistorialFacturaPagoV::join('Clientes', 'historial_factura_pagoV.ClienteId', 'Clientes.ClienteId')
                ->where([['Status', 'ACTIVO'], ['EstadoDelServicio', 'Suspendido'], ['total_deuda', '<=', 100]])
                ->count();
           
            $parametros['suspenciones'] = HistorialFacturaPagoV::whereRaw("Status = 'ACTIVO' AND EstadoDelServicio = 'Activo' AND total_deuda > 0")
                ->join('Clientes', 'historial_factura_pagoV.ClienteId', 'Clientes.ClienteId')
                ->count();

            $parametros['clientes_aprovicionar'] = Cliente::select('Clientes.ClienteId')
                ->leftJoin('clientes_onts_olts', 'Clientes.ClienteId', 'clientes_onts_olts.ClienteId')
                ->where('Clientes.Status', 'ACTIVO')
                ->whereNull('clientes_onts_olts.ClienteId')
                ->count();
        }
            
        return view('adminlte::home')->with($parametros);

    }
}
