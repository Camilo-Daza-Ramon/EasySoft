<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\ContratoServicio;
use App\Cliente;
use App\ClienteOntOlt;
use App\ClienteContrato;
use App\PlanComercial;
use App\ContratoEvento;
use App\Novedad;
use App\ClienteSuspension;
use DB;

class ContratoServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'contrato' => 'required',            
            'plan_internet' => 'required',
            'cliente_id' => 'required']
        );

        $cliente = Cliente::find($request->cliente_id);
        $cliente->PlanComercial = $request->plan_internet;
        $cliente->AutorizaFacturaElectronica = 'SI';
        $cliente->EmpresaFacturaID = 1;

        //Valor de la tarifa de internet
        $planes_tarifas = PlanComercial::findOrFail($request->plan_internet);
        $cliente->ValorTarifaInternet = $planes_tarifas->ValorDelServicio;

        if ($cliente->save()) {          

            $servicio = new ContratoServicio;
            $servicio->nombre = $planes_tarifas->nombre;
            $servicio->descripcion = $planes_tarifas->DescripcionPlan;
            $servicio->cantidad = $planes_tarifas->VelocidadInternet;
            $servicio->unidad_medida = 'Megas';
            $servicio->valor = $planes_tarifas->ValorDelServicio;
            $servicio->tipo_servicio = 'INTERNET';

            if ($cliente->Estrato < 3) {
                $servicio->iva = false;
            }else if ($cliente->Estrato >= 3){
                $servicio->iva = true;
            }
            
            $servicio->estado = 'PENDIENTE';
            $servicio->contrato_id = $request->contrato;

            $servicio->save();

            return redirect()->route('clientes.contratos.show', [$request->cliente_id, $request->contrato])->with('success', 'Servicio creado.');

        }else{
            return redirect()->route('clientes.contratos.show', [$request->cliente_id, $request->contrato])->with('success', 'Error al agregar el servicio.');
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
        $this->validate(request(),[
            'estado' => 'required',
            'fsp' => 'required',
            'ont_id' => 'required'
        ]);

        $servicio = ContratoServicio::find($id);     
        $contrato_id = $servicio->contrato_id;

        $result = DB::transaction(function () use($request, $id, $servicio) {

            $estado_servicio = $servicio->estado;
            $servicio->estado = $request->estado;

            $contrato_id = $servicio->contrato_id;

            $cliente = Cliente::find($servicio->contrato->ClienteId);
            $cliente->EstadoDelServicio = $request->estado;
            

            $estado = '';

            $ont_id = $cliente->cliente_ont_olt->id;

            if($request->estado == 'Activo'){
                
                //$suspensiones_temporales = $cliente->suspensiones_temporales->where('estado', 'ACTIVA')->count();

                /*if(isset($cliente->historial_factura_pago)){ //&& $suspensiones_temporales == 0){
                    if($cliente->historial_factura_pago->total_deuda > 100){
                        DB::rollBack();
                        return ['warning', 'No se permite activar un usuario con mora.'];
                    }
                }*/

                /*if($suspensiones_temporales > 0){
                    DB::rollBack();
                    return ['warning', 'Esta acción no esta permitida. El cliente tiene una Suspensión Temporal ACTIVA, debe finalizarla en el modulo correspondiente.'];
                }*/
            }

            switch ($request->estado) {
                case 'Activo':
                    $estado = 'activate';
                    $cliente->Status = 'ACTIVO';                    
                    break;

                case 'Suspendido':
                    $estado = 'deactivate';
                    $cliente->Status = 'ACTIVO';
                    break;

                case 'Inactivo':
                    $estado = 'deactivate';
                   
                    break;
            }

            try {

                $fsp = explode('/', $request->fsp);

                $ip = $cliente->cliente_ont_olt->olt->ip;
                $usuario = $cliente->cliente_ont_olt->olt->usuario;
                $pass = Crypt::decrypt($cliente->cliente_ont_olt->olt->password);//'Web*-*("#)126Q';//

                #Informacion ONT
                $client = new \Bestnetwork\Telnet\TelnetClient($ip);
                $client->login($usuario, $pass);

                $command = 'enable';
                $client->execute($command);

                $command = 'config';
                $client->execute($command);

                $command = 'interface gpon ' . $fsp[0] . '/' . $fsp[1];
                $client->execute($command);

                $command = 'ont '. $estado .' '. $fsp[2] .' ' . $request->ont_id;
                $client->execute($command);

                $client->disconnect();
                
            } catch (\Exception $e) {                
                return ['error', $e->getMessage()];
            }

            if ($servicio->save()) {
                if ($cliente->save()) {
                    $ont = ClienteOntOlt::find($ont_id);
                    $ont->user_id = Auth::user()->id;

                    if($ont->save()){
                        $contrato = ClienteContrato::find($servicio->contrato_id);

                        if ($contrato->estado == 'PENDIENTE' && $cliente->Status != 'INACTIVO') {
                            $contrato->estado = 'VIGENTE';
                        }

                        if ($contrato->save()) {
                            #Registro de Evento
                            $evento = new ContratoEvento;
                            $evento->accion = $request->estado;
                            $evento->descripcion = "El usuario " . Auth::user()->name . " ".$request->estado." el servicio " . $servicio->id;
                            $evento->user_id = Auth::user()->id;
                            $evento->contrato_id = $contrato->id;
                            
                            if(!$evento->save()){
                                DB::rollBack();
                                return ['error', 'No registró el evento.'];
                            }
                        }else{
                            DB::rollBack();
                            return ['error', 'No actualizó el contrato.'];
                        }

                        $novedad_pendiente = Novedad::where('ClienteId', $cliente->ClienteId)
                            ->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora'])
                            ->whereNull('fecha_fin')
                            ->first();

                        if (($estado_servicio == 'Suspendido' && $request->estado == 'Activo') || ($estado_servicio == 'Activo' && $request->estado == 'Activo')) {                            

                            if (!empty($novedad_pendiente)) {
                                $novedad_pendiente->fecha_fin = date('Y-m-d H:i:s');
                                $novedad_pendiente->estado = 'SALDADO';

                                if (!$novedad_pendiente->save()) {
                                    DB::rollBack();
                                    return ['error', 'No actualizó la novedad pendiente.'];
                                }

                                if($cliente->proyecto->costo->count() > 0 && in_array($cliente->municipio_id, [406,407,420])){

                                    $key = array_search('Reconexión', array_column($cliente->proyecto->costo->toArray(), 'concepto'));
                                    
                                    if($key !== FALSE){

                                        $validar = Novedad::where([
                                            ['ClienteId', $cliente->ClienteId], 
                                            ['concepto', 'Reconexión'],
                                            ['fecha_inicio', '>', date('Y-m').'-01']
                                        ])->count();

                                        if($validar <= 0){

                                            $novedad = new Novedad;
                                            $novedad->concepto = 'Reconexión';
                                            $novedad->cantidad = 1;

                                            $novedad->valor_unidad = $cliente->proyecto->costo[$key]['valor'];
                                            $novedad->iva = ($cliente->proyecto->costo[$key]['iva'] == 'SI' && $cliente->Estrato > 3)? 19 : null;
                                            
                                            $novedad->fecha_inicio = date('Y-m-d H:i:s');
                                            $novedad->fecha_fin = date('Y-m-d H:i:s');
                                            
                                            $novedad->estado = 'PENDIENTE';
                                            $novedad->ClienteId = $cliente->ClienteId;

                                            $novedad->cobrar = true;
                                            $novedad->unidad_medida = 'UNIDAD';
                                            $novedad->user_id = 1;
                                        
                                            if (!$novedad->save()) {
                                                DB::rollBack();
                                                return ['error', 'No se logró crear la novedad.'];
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }else if (($estado_servicio == 'Activo' && $request->estado == 'Suspendido') || ($estado_servicio == 'Suspendido' && $request->estado == 'Suspendido')){

                            if (empty($novedad_pendiente)) {
                                $novedad = new Novedad();
                                $novedad->concepto = 'Suspensión por Mora';
                                $novedad->fecha_inicio = date('Y-m-d H:i:s');
                                $novedad->estado = 'PENDIENTE';
                                $novedad->cobrar = false;
                                $novedad->unidad_medida = 'MINUTOS';
                                $novedad->user_id = Auth::user()->id;
                                $novedad->ClienteId = $cliente->ClienteId;

                                if (!$novedad->save()) {
                                    DB::rollBack();
                                    return ['error', 'No se logró crear la novedad.'];
                                }
                            }                            
                        }else if($request->estado == 'Inactivo'){
                            if (!empty($novedad_pendiente)) {
                                DB::rollBack();
                                return ['error', 'El cliente tiene novedades pendientes o por finalizar.'];
                            }
                        }                       


                    }else{
                        DB::rollBack();
                        return ['error', 'No actualizó el usuario.'];
                    }
                }else{
                    DB::rollBack();
                    return ['error', 'No actualizó el cliente.'];
                }

                return ['success', 'Estado cambiado.'];
                
            }else{
                DB::rollBack();
                return ['error', 'No actualizó el servicio.'];
            }
        });

        return redirect()->route('clientes.contratos.show', [$servicio->contrato->ClienteId, $contrato_id])->with($result[0], $result[1]);

                
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if (Auth::user()->can('contratos-servicios-eliminar')) {
            $servicio = ContratoServicio::findOrFail($id);
            $contrato = $servicio->contrato_id;

            #Registro de Evento
            $evento = new ContratoEvento;
            $evento->accion = 'Eliminar';
            $evento->descripcion = "El usuario " . Auth::user()->name . " eliminó el Servicio #". $servicio->id ." Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;
            $evento->user_id = Auth::user()->id;
            $evento->contrato_id = $contrato;

            if($evento->save()){
                if ($servicio->delete()) {
                    return redirect()->route('clientes.contratos.show', [$servicio->contrato->ClienteId, $contrato])->with('success','Servicio eliminado.');
                }else{
                    return redirect()->route('clientes.contratos.show', [$servicio->contrato->ClienteId, $contrato])->with('error','No se pudo eliminar.');
                }            
            }else{
                return redirect()->route('clientes.contratos.show', [$servicio->contrato->ClienteId, $contrato])->with('error','No se pudo eliminar.');
            }
        }else{
            abort(403);
        }
    }
}
