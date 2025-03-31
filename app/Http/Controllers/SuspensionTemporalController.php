<?php

namespace App\Http\Controllers;

use App\SuspensionTemporal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Cliente;
use App\ContratoEvento;

use App\Custom\ONT;
use DB;

use Illuminate\Support\Facades\Auth;

class SuspensionTemporalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('suspensiones-temporales-listar')) {

            $suspensiones = SuspensionTemporal::Cedula($request->get('palabra'))
            ->Estado($request->get('estado'))
            ->latest()
            ->paginate(15);

            $estados = [
                'ACTIVA',
                'CANCELADA',
                'FINALIZADA',
                'PENDIENTE'
            ];

            return view('adminlte::atencion-clientes.suspensiones-temporales.index',compact('suspensiones','estados'))->with('i', ($request->input('page', 1) - 1) * 10);

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
        if (Auth::user()->can('suspensiones-temporales-crear')) {

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
        if (Auth::user()->can('suspensiones-temporales-crear')) {

            $this->validate(request(),[
                'descripcion' => 'required',
                'cliente_id' => 'required',
                'mes_inicio' => 'required',
                'fecha_fin' => 'required',
                'fecha_solicitud' => 'required'
            ]);

            $validar = SuspensionTemporal::select('id')->where([
                ['cliente_id', $request->cliente_id],
                ['estado', 'PENDIENTE']
            ])->count();


            if($validar > 0){
                return redirect()->route('suspensiones-temporales.index')->with('warning','El cliente ya tiene una suspensión PENDIENTE');
            }

            $validar = SuspensionTemporal::select('id')->where([
                ['cliente_id', $request->cliente_id],
                ['estado', 'ACTIVA']
            ])->count();            

            if($validar > 0){
                return redirect()->route('suspensiones-temporales.index')->with('warning','El cliente ya tiene una suspensión ACTIVA');
            }

            $validar = SuspensionTemporal::selectRaw('cliente_id, SUM(DATEDIFF(DAY, fecha_hora_inicio, fecha_hora_fin)) AS total_dias')
            ->where([
                ['cliente_id', $request->cliente_id],
                ['estado', 'FINALIZADA']
            ])
            ->whereBetween('fecha_hora_inicio', [date('Y') . '-01-01 00:00:00', date('Y-m-d H:i:s')])
            ->groupBy('cliente_id')
            ->first();

            if(!empty($validar)){
                if($validar->total_dias >= 60){
                    return redirect()->route('suspensiones-temporales.index')->with('warning','El cliente ya cumplió con el maximo tiempo permitido por año.');
                }
            }            

            $suspension = new SuspensionTemporal;
            $suspension->descripcion = $request->descripcion;
            $suspension->cliente_id = $request->cliente_id;
            $suspension->user_id = Auth::user()->id;
            $suspension->fecha_hora_inicio = $request->mes_inicio . "-01 00:00:00";
            $suspension->fecha_hora_fin = $request->fecha_fin . " 23:59:59";
            $suspension->fecha_solicitud = $request->fecha_solicitud;
            $suspension->estado = 'PENDIENTE';

            if($suspension->save()){
                return redirect()->route('suspensiones-temporales.index')->with('success','Suspensión creada correctamente.');
            }else{
                return redirect()->route('suspensiones-temporales.index')->with('error','Error al crear la suspensión');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SuspensionTemporal  $suspension_temporal
     * @return \Illuminate\Http\Response
     */
    public function show(SuspensionTemporal $suspension_temporal)
    {
        if (Auth::user()->can('suspensiones-temporales-ver')) {

            return response()->json([
                'suspension' => $suspension_temporal, 
                'cliente' => $suspension_temporal->cliente, 
                'usuario' => $suspension_temporal->user
            ]);

        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SuspensionTemporal  $suspension_temporal
     * @return \Illuminate\Http\Response
     */
    public function edit(SuspensionTemporal $suspension_temporal)
    {
        if (Auth::user()->can('suspensiones-temporales-editar')) {

            $total_tiempo = 60;

            $validar = SuspensionTemporal::selectRaw('cliente_id, SUM(DATEDIFF(DAY, fecha_hora_inicio, fecha_hora_fin)) AS total_dias')
            ->where([                
                ['estado', 'FINALIZADA'],
                ['cliente_id', $suspension_temporal->cliente_id]
            ])
            ->whereBetween('fecha_hora_inicio', [date('Y') . '-01-01 00:00:00', date('Y-m-d H:i:s')])
            ->groupBy('cliente_id')
            ->first();

            if(!empty($validar)){
                $total_tiempo = $validar->total_dias;
            }


            return response()->json([
                'suspension' => $suspension_temporal,
                'cliente' => $suspension_temporal->cliente,
                'total_tiempo' => $total_tiempo
            ]);
        }else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SuspensionTemporal  $suspension_temporal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuspensionTemporal $suspension_temporal)
    {
        if (Auth::user()->can('suspensiones-temporales-editar')) {
            
            $this->validate(request(),[
                'descripcion' => 'required',
                'mes_inicio' => 'required',
                'fecha_fin' => 'required',
                'fecha_solicitud' => 'required',
                'estado' => 'required'
            ]);

            if($suspension_temporal->estado == 'ACTIVA' || $suspension_temporal->estado == 'PENDIENTE'){

                $result = DB::transaction(function () use($request, $suspension_temporal) {

                    $suspension_temporal->descripcion = $request->descripcion;
                    $suspension_temporal->user_id = Auth::user()->id;
                    $suspension_temporal->fecha_hora_inicio = $request->mes_inicio . '-01 00:00:00';
                    $suspension_temporal->fecha_hora_fin = $request->fecha_fin . ' 23:59:59';

                    if($request->estado == $suspension_temporal->estado){

                    }else if($request->estado == 'FINALIZADA' && $suspension_temporal->estado == 'ACTIVA'){

                        $suspension_temporal->fecha_hora_fin = date('Y-m-d H:i:s');
                        $suspension_temporal->estado = $request->estado;

                        $olt = $suspension_temporal->cliente->cliente_ont_olt->olt;
                        $serial = $suspension_temporal->cliente->cliente_ont_olt->activo->Serial;

                        $ip = $olt->ip;
                        $usuario = $olt->usuario;
                        $pass = $olt->password;

                        $cliente = $suspension_temporal->cliente;
                        $cliente->EstadoDelServicio = 'Activo';

                        if(!$cliente->save()){
                            DB::rollBack();
                            return ['error', 'Error al actualizar el estado del servicio del cliente.'];
                        }

                        $ont = new ONT($ip, $usuario, $pass);
                        $resultado = $ont->ejecutar($olt->version, $serial, $cliente, 'Activo', Auth::user()->id);

                        if($resultado[0] == 'success'){

                            $novedad = $suspension_temporal->novedad;
                            $novedad->fecha_fin = $suspension_temporal->fecha_hora_fin;
                            $novedad->user_id = Auth::user()->id;

                            if(!$novedad->save()){
                                DB::rollBack();
                                return ['error', 'Error al actualizar la novedad.'];
                            }

                        }else{
                            DB::rollBack();
                            return $resultado;
                        }


                    }else{
                        DB::rollBack();
                        return ['error', 'No es posible realizar esta acción.'];
                    }

                    if($suspension_temporal->save()){
                        return ['success', 'Suspensión actualizada correctamente.'];
                    }else{
                        DB::rollBack();
                        return ['error', 'Error al actualizar la suspensión'];
                    }
                });

                return redirect()->route('suspensiones-temporales.index')->with($result[0],$result[1]);

            }else{
                return redirect()->route('suspensiones-temporales.index')->with('error','No esta permitido hacer esto.');
            }

        }else{
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SuspensionTemporal  $suspension_temporal
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuspensionTemporal $suspension_temporal)
    {
        if (Auth::user()->can('suspensiones-temporales-cancelar')) {

                if($suspension_temporal->estado != 'PENDIENTE'){
                    return redirect()->route('suspensiones-temporales.index')->with('warging', 'No es posible cancelar la suspension ' . $suspension_temporal->estado);
                }

                if(!empty($suspension_temporal->novedad_id)){
                    return redirect()->route('suspensiones-temporales.index')->with('warging', 'No es posible cancelar porque tiene una novedad asociada.');
                }

                if(!empty($suspension_temporal->solicitud_id)){
                    return redirect()->route('suspensiones-temporales.index')->with('warging', 'No es posible cancelar porque tiene una solicitud asociada.');
                }

                $suspension_temporal->estado = 'CANCELADA';
                $suspension_temporal->user_id = Auth::user()->id;

                if($suspension_temporal->save()){
                    return redirect()->route('suspensiones-temporales.index')->with('success', 'Suspensión Cancelada Correctamente.');
                }else{
                    return redirect()->route('suspensiones-temporales.index')->with('error', 'Error al intentar cancelar la suspensión.');
                }


        }else{
            abort(403);
        }
    }

    public function ajax(Request $request){

        $this->validate(request(),[
            'cedula' => 'required|numeric|min:4'
        ]);

        if ($request->ajax()) {
            
            $cliente = "";
            $total_tiempo = 60;
            $error = "";
            $total_deuda = 0;

            $validar = SuspensionTemporal::selectRaw('cliente_id, SUM(DATEDIFF(DAY, fecha_hora_inicio, fecha_hora_fin)) AS total_dias')
            ->leftJoin('Clientes as c', 'suspensiones_temporales.cliente_id', 'c.ClienteId')                     
            ->where([                
                ['estado', 'FINALIZADA'],
                ['c.Identificacion', $request->cedula]
            ])
            ->whereBetween('fecha_hora_inicio', [date('Y') . '-01-01 00:00:00', date('Y-m-d H:i:s')])
            ->groupBy('cliente_id')
            ->first();            

            if(empty($validar)){
                $validar = Cliente::select('ClienteId','NombreBeneficiario', 'Apellidos')->where('Identificacion', $request->cedula)->first();
                
                if(empty($validar)){
                    return null;
                }else{
                    $validar->cliente_id = $validar->ClienteId;
                    $cliente = $validar->NombreBeneficiario . ' ' . $validar->Apellidos;
                    $total_deuda = $validar->historial_factura_pago->total_deuda;
                }                

            }else{
                $cliente = $validar->cliente->NombreBeneficiario . ' ' . $validar->cliente->Apellidos;
                $total_tiempo = $validar->total_dias;
                $total_deuda = $validar->cliente->historial_factura_pago->total_deuda;

                if(intval($total_tiempo) >= 60){
                    $error = "El cliente ya ha suspendido su servicio por " . $total_tiempo . " días";
                } 
            }

            if($total_deuda > 0){
                $error = "El cliente tiene una deuda pendiente por: $" . number_format($total_deuda, 2, ",",".");
            }

            

            return response()->json(['cliente_id' => $validar->cliente_id, 'total_tiempo' => $total_tiempo, 'nombre' => $cliente, 'error' => $error]);

        }
    }

}
