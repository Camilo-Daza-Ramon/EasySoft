<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Custom\WebService2;
use App\Olt;
use App\Departamento;
use App\Novedad;



use App\Ticket;
use App\TicketMedioAtencion;
use App\TipoFallo;
use App\TicketTipoPrueba;
use App\Proyecto;
use App\EstadoTicket;
use App\TicketPrueba;
use App\Mantenimiento;
use App\User;
use DB;




class OltsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('olts-listar')) {
            $olts = Olt::Buscar($request->get('palabra'), $request->get('municipio'))->paginate(15);
            $departamentos = Departamento::where('Status', 'A')->orderBy('NombreDelDepartamento', 'ASC')->get();        
            return view('adminlte::red.olts.index',compact('olts', 'departamentos'));
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
        /*if (Auth::user()->hasRole('admin')) {
            $result = DB::transaction(function () {

                $ticket = Ticket::findOrFail(161941);

                #Creamos el mantenimiento
                $total_mantenimientos = Mantenimiento::where('Fecha', '>', date('Y').'-01-01')->count();

                if ($total_mantenimientos == 0) {
                    $total_mantenimientos = 1;
                }

                $mantenimiento = new Mantenimiento;
                $mantenimiento->TipoMantenimiento = 'COR';
                $mantenimiento->ProyectoId = $ticket->cliente->ProyectoId;
                $mantenimiento->DescripcionProblema = $ticket->Observacion;
                //$mantenimiento->UbicacionId = ;
                $mantenimiento->Fecha = date('Y-m-d H:i:s');
                $mantenimiento->FechaMaxima = date('Y-m-d',strtotime(date('Y-m-d')."+ 2 day"));
                $mantenimiento->ClienteId = $ticket->ClienteId;
                $mantenimiento->UserId = 1258;
                $mantenimiento->TicketId = $ticket->TicketId;
                $mantenimiento->CorreoCliente = $ticket->cliente->CorreoElectronico;
                $mantenimiento->Clase = 'C';
                $mantenimiento->NumeroDeTicket = 'MC-'.date('y').'-'.str_pad($total_mantenimientos, 8, "0", STR_PAD_LEFT);
                $mantenimiento->Estado = 'Abierto';
                //$mantenimiento->AgenteCreaMantenimiento = 1258;
                $mantenimiento->DepartamentoId = $ticket->cliente->municipio->DeptId;
                $mantenimiento->MunicipioId = $ticket->cliente->municipio_id;
                $mantenimiento->TipoEntrada = $ticket->TipoDeEntrada;
                $mantenimiento->user_crea = Auth::user()->id;

                if (!$mantenimiento->save()) {
                    DB::rollBack();
                    return ['respuesta' => 'Error al escalar a mantenimiento', 'tipo_mensaje' => 'error'];
                }
            


                $novedad = new Novedad;
                $novedad->concepto = 'Ajustes por falta de servicio';
                $novedad->fecha_inicio = date('Y-m-d H:i:s');
                $novedad->estado = 'PENDIENTE';
                $novedad->unidad_medida = 'MINUTOS';
                $novedad->ClienteId = $ticket->ClienteId;
                $novedad->cobrar = false;
                $novedad->user_id = Auth::user()->id;
                $novedad->ticket_id = $ticket->TicketId;

                if (!$novedad->save()) {
                    DB::rollBack();
                    return ['respuesta' => 'Error al agregar novedad', 'tipo_mensaje' => 'error'];
                }

                return ['respuesta' => 'todo bien', 'tipo_mensaje' => 'success'];
                
            });

            dd($result);
        }*/
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->can('olts-crear')) {

            $this->validate(request(),[
                'nombre' => 'required',
                'ip' => 'required',
                'usuario' => 'required',
                'password' => 'required',
                'municipio' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'estado' => 'required',
                'version' => 'required'
            ]);

            $olt = new Olt;
            $olt->nombre = $request->nombre;
            $olt->ip = $request->ip;
            $olt->usuario = $request->usuario;
            $olt->password = Crypt::encrypt($request->password);
            $olt->latitud = $request->latitud;
            $olt->longitud = $request->longitud;
            $olt->municipio_id = $request->municipio;
            $olt->estado = $request->estado;
            $olt->version = $request->version;

            if ($olt->save()) {
                return redirect()->route('red.olts.index')->with('success', 'Olt agregada.');
            }else{
                return redirect()->route('red.olts.index')->with('error', 'Error al agregar OLT.');
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

        if (Auth::user()->can('olts-ver')) {

            $olt = Olt::findOrFail($id);
            $olt->password = Crypt::decrypt($olt->password);

            return response()->json($olt);

        }else{
            abort(403);
        }

        /*$periodo = date("Y-m",strtotime('2021-10'."- 1 month"));


        $novedades2 = Novedad::where([
            ['ClienteId', 466453],
            ['cobrar', false]
        ])
        ->where('fecha_inicio','<', date("Y-m-t",strtotime($periodo)))
        ->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora', 'Ajustes por falta de servicio'])
        ->whereNull('fecha_fin')
        ->get();

        dd($novedades2);*/

       /* $reportar = new WebService2;

        $datos = array();
        //$datos[] = $reportar->listar_cliente();
        //$datos[] = $reportar->clientes_sin_onts();
        //$datos[] = $reportar->clientes_no_activos();
        $datos[] = $reportar->listar_cliente(519);

        dd(json_encode($datos));
        
        return view('adminlte::red.olts.show', compact('datos'));*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('olts-editar')) {
            $olt = Olt::with('municipio')->select('nombre','ip','usuario','latitud','longitud','municipio_id','estado','version')->findOrFail($id);
            return response()->json($olt);
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
        if (Auth::user()->can('olts-editar')) {
            $this->validate(request(),[
                'nombre' => 'required',
                'ip' => 'required',
                'usuario' => 'required',
                'municipio' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'estado' => 'required'
            ]);

            $olt = Olt::find($id);
            $olt->nombre = $request->nombre;
            $olt->ip = $request->ip;
            $olt->usuario = $request->usuario;
            if (!empty($request->password)) {
                $olt->password = Crypt::encrypt($request->password);
            }
            
            $olt->latitud = $request->latitud;
            $olt->longitud = $request->longitud;
            $olt->municipio_id = $request->municipio;
            $olt->estado = $request->estado;

            if ($olt->save()) {
                return redirect()->route('red.olts.index')->with('success', 'Olt actualizada.');
            }else{
                return redirect()->route('red.olts.index')->with('error', 'Error al actualizar OLT.');
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
}
