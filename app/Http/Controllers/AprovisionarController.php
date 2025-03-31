<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ClienteOntOlt;
use App\ClienteContrato;
use App\ContratoServicio;

use App\Cliente;
use App\ActivoFijo;
use App\ContratoEvento;
use App\Proyecto;
use App\ReporteOntFallida;
use Excel;
use DB;

class AprovisionarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('aprovisionar-listar')) {
            $clientes = Cliente::select('Clientes.*','cc.id as contrato_id')
                        ->leftJoin('clientes_onts_olts', 'Clientes.ClienteId', 'clientes_onts_olts.ClienteId')
                        ->join('clientes_contratos as cc', 'Clientes.CLienteId', 'cc.ClienteId')
                        ->Cedula($request->get('documento'))
                        ->Proyecto($request->get('proyecto'))
                        ->Departamento($request->get('departamento'))
                        ->Municipio($request->get('municipio'))
                        ->where([['Clientes.Status','ACTIVO'], ['cc.estado' , 'VIGENTE']])
                        ->whereNull('clientes_onts_olts.ClienteId')
                        ->paginate(15);

            $proyectos = Proyecto::select('ProyectoID', 'NumeroDeProyecto')->get();

            return view('adminlte::clientes.aprovisionar.index', compact('clientes', 'proyectos'));
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
        $this->validate(request(),[
            'cliente_id' => 'required',
            'ont_id' => 'required',
            'olt_id' => 'required',
            'servicio_id' => 'required',
            'fecha_instalacion' => 'required'
        ]);

        $result = DB::transaction(function () use($request) {

            $serial = "";

            $aprovicionar = new ClienteOntOlt;
            $aprovicionar->ClienteId = $request->cliente_id;
            $aprovicionar->ActivoFijoId = $request->ont_id;
            $aprovicionar->olt_id = $request->olt_id;
            $aprovicionar->user_id = Auth::user()->id;

            if ($aprovicionar->save()) {                

                $ont = ActivoFijo::findOrFail($request->ont_id);
                $ont->Estado = 'ASIGNADA';
                $serial = $ont->Serial;

                if(!$ont->save()){
                    DB::rollBack();
                    return ['error', 'No se actualizó el estado en el inventario'];
                }

                $servicio = ContratoServicio::find($request->servicio_id);

                if ($request->estado != 'Sin acciones') {
                    $cliente = Cliente::find($request->cliente_id);
                    $cliente->EstadoDelServicio = $request->estado;

                    if ($request->estado == 'Activo' || $request->estado == 'Suspendido') {
                        $cliente->Status = 'ACTIVO';
                    }else {
                        $cliente->Status = 'INACTIVO';
                    }

                    if (!$cliente->save()) {
                        DB::rollBack();
                        return ['error', 'No se pudo cambiar el estado del cliente.'];
                    }
                    

                    if ($request->estado == 'Activo'){
                        
                        $servicio->estado = $request->estado;

                        if(!$servicio->save()){
                            DB::rollBack();
                            return ['error', 'No se cambió el estado del servicio.'];
                        }                        

                        $contrato = ClienteContrato::find($servicio->contrato_id);
                        $contrato->fecha_instalacion = $request->fecha_instalacion;

                        if ($contrato->estado == 'PENDIENTE') {
                            $contrato->estado = 'VIGENTE';
                        }

                        if (!$contrato->save()) {
                            DB::rollBack();
                            return ['error', 'No se pudo cambiar el estado del contrato'];
                        }
                    }
                    
                }

                #Registro de Evento
                $evento = new ContratoEvento;
                $evento->accion = 'Agregar';
                $evento->descripcion = "El usuario " . Auth::user()->name . " Agrega la ONT " .$serial . " El estado del cliente cambió a: " . $request->estado;
                $evento->user_id = Auth::user()->id;
                $evento->contrato_id = $servicio->contrato_id;
                
                if(!$evento->save()){
                    DB::rollBack();
                    return ['error', 'No registró el evento.'];
                }

                return ['success', 'Ont Agregada'];

            }else{
                DB::rollBack();
                return ['error', 'No guardó la información del aprovisionamiento.'];
            }
        });

        return redirect()->route('clientes.show', $request->cliente_id)->with($result[0], $result[1]);

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
        $aprovicionar = ClienteOntOlt::findOrFail($id);
        $ont_id = $aprovicionar->ActivoFijoId;
        $cliente_id = $aprovicionar->ClienteId;

        $contrato = ClienteContrato::select('id')->where('ClienteId', $cliente_id)->orderBy('id','DESC')->first();

        
        #Registro de Evento
        $evento = new ContratoEvento;
        $evento->accion = 'Eliminar';
        $evento->descripcion = "El usuario " . Auth::user()->name . " eliminó la ONT ". $aprovicionar->activo->Serial;
        $evento->user_id = Auth::user()->id;
        $evento->contrato_id = $contrato->id;
        $evento->save();


        if ($aprovicionar->delete()) {
            $ont = ActivoFijo::findOrFail($ont_id);
            $ont->Estado = 'DISPONIBLE';
            $ont->save();

            $reporteOntFallida = ReporteOntFallida::where('ONT_Serial', '=', $ont->Serial);

            if (!$reporteOntFallida->exists()) {
                return redirect()->route('clientes.show', $cliente_id)->with('success', 'Ont Eliminada');
            }

            if (!$reporteOntFallida->delete()) {
                return redirect()->route('clientes.show', $cliente_id)->with('error', 'No se pudo Eliminar.');
            }

            return redirect()->route('clientes.show', $cliente_id)->with('success', 'Ont Eliminada');

        }else{
            return redirect()->route('clientes.show', $cliente_id)->with('error', 'No se pudo Eliminar.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importar(Request $request)
    {
        //$path = $request->file('archivo')->getRealPath();
        $path = $request->file('archivo');
        $data = Excel::load($path, function($reader){ 
        })->get();

        $total_registros = 0;

        foreach ($data as $key => $value) {

            $total_registros += 1;

            $cliente = Cliente::select('ClienteId', 'Status')->where('Identificacion' , $value->cedula)->first();

            $inventario = ActivoFijo::select('ActivoFijoId','Estado', 'Serial')->where('Serial', $value->serial)->first();

            if (isset($cliente->ClienteId)) {
                $aprovicionar = new ClienteOntOlt;
                $aprovicionar->ClienteId = $cliente->ClienteId;
                $aprovicionar->ActivoFijoId = $inventario->ActivoFijoId;
                $aprovicionar->olt_id = $value->olt;
                $aprovicionar->user_id = Auth::user()->id;

                if ($aprovicionar->save()) {
                    $inventario->Estado = 'ASIGNADA';
                    $inventario->save();
                }
            }
        }

        return Response()->json(array('total' => $total_registros));

    }

    public function exportar(Request $request){

        
        if (Auth::user()->can('aprovisionar-exportar')) {

            Excel::create('Aprovisionamientos-Pendientes', function($excel) use($request) {
     
                $excel->sheet('Aprovisionamientos-Pendientes', function($sheet) use($request) {

                    $datos = array();

                    $clientes = Cliente::select('Clientes.*')
                        ->leftJoin('clientes_onts_olts', 'Clientes.ClienteId', 'clientes_onts_olts.ClienteId')
                        ->leftJoin('clientes_contratos', 'Clientes.ClienteId','clientes_contratos.ClienteId')
                        ->Proyecto($request->get('proyecto'))
                        ->Departamento($request->get('departamento'))
                        ->Municipio($request->get('municipio'))
                        ->where('Clientes.Status','ACTIVO')
                        ->whereNull('clientes_onts_olts.ClienteId')
                        ->get();

                    foreach ($clientes as $cliente) {                        

                         $datos[] = array(
                            'CLIENTE ID' => $cliente->ClienteId,
                            'PROYECTO' => $cliente->proyecto->NumeroDeProyecto,
                            'TIPO DOCUMENTO' => $cliente->TipoDeDocumento,
                            'IDENTIFICACION' => $cliente->Identificacion,
                            'NOMBRE' => $cliente->NombreBeneficiario,
                            'APELLIDO' => $cliente->Apellidos,
                            'TELEFONO' => $cliente->TelefonoDeContactoFijo,
                            'CELULAR' => $cliente->TelefonoDeContactoMovil,
                            'CORREO' => $cliente->CorreoElectronico,
                            'ESTRATO' => $cliente->Estrato,
                            'DIRECCION' => $cliente->DireccionDeCorrespondencia,
                            'BARRIO' => $cliente->Barrio,
                            'MUNICIPIO' => $cliente->municipio->NombreMunicipio,
                            'DEPARTAMENTO' => $cliente->municipio->departamento->NombreDelDepartamento,
                            'FECHA INSTALACION' => (isset($cliente->contrato))? $cliente->contrato[count($cliente->contrato)-1]->fecha_instalacion : '',
                            'ESTADO' => $cliente->Status
                        );

                    }
                    

                    if (count($datos) == 0) {
                        return redirect()->route('clientes.index')->with('warning', 'No hay datos para el filtro enviado.');
                    }

                    //$sheet->fromArray($datos, null, 'A0', false, false);

                    $sheet->fromArray($datos);
     
                });
            })->export('xlsx');
        }else{
            abort(403);
        }
    }
}
