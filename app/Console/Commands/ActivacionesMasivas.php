<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use App\Cliente;
use App\Olt;
use App\ClienteContrato;
use App\ContratoServicio;
use App\ContratoEvento;
use App\Novedad;
use App\ClienteSuspension;
use App\Recaudo;
use DB;
use Carbon\Carbon;

class ActivacionesMasivas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ActivacionesMasivas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activar a los clientes que se pongan al día en sus pagos.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $clientes = Cliente::select('Clientes.ClienteId', 'Clientes.Identificacion','Clientes.municipio_id', 'Clientes.ProyectoId')
            ->join('historial_factura_pagoV', 'historial_factura_pagoV.ClienteId', 'Clientes.ClienteId')
            ->where([
                ['Status', 'ACTIVO'],
                ['EstadoDelServicio', 'Suspendido'],
                ['total_deuda', '<=', 100]
            ])
            ->get();

        $this->recorrer_clientes($clientes, false);

                  
        $this->clientes_pagan_mora();
        
        
    }

    private function recorrer_clientes($clientes, $validar_mora){

        if (count($clientes) > 0) {
            
            foreach ($clientes as $cliente) {

                if($validar_mora){
                    if(($cliente->mora - $cliente->total_pago) <= 100){

                    }else{
                        continue;
                    }
                }


                $olt = Olt::where('municipio_id', $cliente->municipio_id)->first();
                $error = array();
                
                try { 
                    $ip = $olt->ip;
                    $usuario = $olt->usuario;
                    $pass = Crypt::decrypt($olt->password);
                    
                    #Informacion ONT
                    $client = new \Bestnetwork\Telnet\TelnetClient($ip);
                    $client->login($usuario, $pass);

                    $command = 'enable';
                    $client->execute($command);

                    $command = 'config';
                    $client->execute($command);

                    $n = 0;
                    #para cada uno de los clientes se debe ejecutar estado

                    $resp = "";
                    $response = "";

                    $i = 2;

                    $command = 'display ont info by-sn ' . $cliente->cliente_ont_olt->activo->Serial;

                    if($olt->version == 2){
                        $i = 3;
                        $client->execute($command, ":");
                        $resp = $client->execute("\r\n", "---- More ( Press 'Q' to break ) ----");
                    }else{
                        $resp = $client->execute($command, "---- More ( Press 'Q' to break ) ----");
                    }

                    $client->execute('q');
                    $client->clearBuffer();


                    #hacer algo con la respuesta
                    $response = explode("\r\n", $resp);
                    $error[] = $response;

                    if (count($response) > 4) {
                        $ont = array();
                        $data = "";

                        for ($i; $i < count($response); $i++) {

                            $data = explode(":", $response[$i]);
                            $key = str_replace('  ', '', $data[0]);

                            switch ($key) {
                                case 'Control flag':
                                    $ont[$key] = str_replace(' ', '', $data[1]);
                                    break;
                                
                                case 'Run state ':
                                    $ont[$key] = str_replace(' ', '', $data[1]);
                                    break;

                                case 'Config state':
                                    $ont[$key] = str_replace(' ', '', $data[1]);
                                    break;

                                case 'F/S/P ';
                                    $ont[$key] = str_replace(' ', '', $data[1]);
                                    break;

                                case 'ONT-ID';
                                    $ont[$key] = str_replace(' ', '', $data[1]);
                                    break;

                                default:
                                        
                                    break;
                            }
                        }

                        $fsp = str_replace(' ', '',$ont["F/S/P "]);
                        $ontid = str_replace(' ', '',$ont["ONT-ID"]);

                        $fsp = explode('/', $fsp);

                        $command = 'interface gpon ' . $fsp[0] . '/' . $fsp[1];
                        $client->execute($command);

                        $command = 'ont activate '. $fsp[2] .' ' . $ontid;
                        $client->execute($command);
                        

                        $client->execute('q');
                        $client->clearBuffer();                        

                        $result = DB::transaction(function () use($cliente) {

                            $novedad_pendiente = Novedad::where('ClienteId', $cliente->ClienteId)
                            //->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora'])
                            ->where('concepto', 'Suspensión por Mora')
                            ->whereNull('fecha_fin')
                            ->first();


                            $cliente_actualizar = Cliente::find($cliente->ClienteId);
                            $cliente_actualizar->EstadoDelServicio = 'Activo';

                            if ($cliente_actualizar->save()) {

                                $contrato = ClienteContrato::select('contratos_servicios.id')                    
                                ->join('contratos_servicios','clientes_contratos.id', '=', 'contratos_servicios.contrato_id')
                                ->whereNotIn('clientes_contratos.estado', ['FINALIZADO','ANULADO','PENDIENTE'])
                                ->where([['clientes_contratos.ClienteId', $cliente->ClienteId], ['contratos_servicios.estado', 'Suspendido']])
                                ->get();

                                if ($contrato->count() > 0) {

                                    foreach ($contrato as $servicios) {
                                        $servicio = ContratoServicio::find($servicios->id);
                                        $servicio->estado = 'Activo';

                                        if ($servicio->save()) {

                                            #Registro de Evento
                                            $evento = new ContratoEvento;
                                            $evento->accion = "Activo";
                                            $evento->descripcion = "Servicio #". $servicio->id ." activado por el sistema. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;
                                            $evento->contrato_id = $servicio->contrato_id;

                                            if (!$evento->save()) {
                                                DB::rollBack();
                                                return ['error', 'No se logró crear el registro del evento.'];
                                            }else{  
                                                    
                                                if (!empty($novedad_pendiente)) {

                                                    $novedad_pendiente->fecha_fin = date('Y-m-d H:i:s');
                                                    //$novedad_pendiente->estado = 'SALDADO';

                                                    if (!$novedad_pendiente->save()) {
                                                        DB::rollBack();
                                                        return ['error', 'No actualizó la novedad pendiente.'];
                                                    }
                                                }
                                            }                                    

                                        }else{
                                            DB::rollBack();
                                            return ['error', 'No se logró actualizar el servicio.'];
                                        }
                                    }

                                    if(isset($cliente->proyecto->costo)){


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
                                    }else{
                                        DB::rollBack();
                                        return ['error', 'no tiene costos '. $cliente->Identificacion];
                                    }                      
                                }
                                                    
                            }else{
                                DB::rollBack();
                                return ['error', 'No se logró actualizar el cliente.'];
                            }
                        });

                    }else{
                        break;
                    }
                    #fin                   

                    $client->disconnect();                          

                }catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
            }
        }

    }

    private function clientes_pagan_mora(){

        /*SELECT c.Identificacion, cr.ClienteId, SUM(cr.valor) as Total_pago, f.SaldoEnMora FROM ClientesRecaudos as cr
        INNER JOIN Clientes as c ON cr.ClienteId = c.ClienteId and c.EstadoDelServicio = 'Suspendido'
        INNER JOIN Facturacion as f ON cr.ClienteId = f.ClienteId and f.Periodo = 202412
        WHERE cr.FechaOriginal > '2024-12-06 00:00:00'
        GROUP BY c.Identificacion, cr.ClienteId, f.SaldoEnMora*/

        

        $fecha = date('Y-m');
        $periodo = str_replace('-', '',$fecha);

        /*$clientes = Cliente::selectRaw('Clientes.Identificacion, Clientes.ProyectoId, Clientes.municipio_id, cr.ClienteId, SUM(cr.valor) as total_pago, f.SaldoEnMora as mora')
        ->join('ClientesRecaudos as cr', function($join){
            $join->on('cr.ClienteId', '=', 'Clientes.ClienteId')->where('Clientes.EstadoDelServicio', '=', 'Suspendido');
        })
        ->join('Facturacion as f ', function($join) use($periodo){
            $join->on('cr.ClienteId','=','f.ClienteId')->where('f.Periodo', '=', $periodo);
        })
        ->where('cr.FechaOriginal', '>', $fecha.'-06 00:00:00')
        ->groupBy('Clientes.Identificacion', 'Clientes.ProyectoId', 'Clientes.municipio_id', 'cr.ClienteId', 'f.SaldoEnMora')
        ->get();*/


        /*SELECT c.Identificacion, cr.ClienteId, SUM(cr.valor) as Total_pago, f.SaldoEnMora FROM ClientesRecaudos as cr
        INNER JOIN Clientes as c ON cr.ClienteId = c.ClienteId and c.EstadoDelServicio = 'Suspendido'
        INNER JOIN ultima_facturaV as uf ON cr.ClienteId = uf.cliente_id
        INNER JOIN Facturacion as f ON uf.factura_id = f.FacturaId
        WHERE cr.FechaOriginal > '2025-01-01 00:00:00' and f.SaldoEnMora > 0
        GROUP BY c.Identificacion, cr.ClienteId, f.SaldoEnMora*/

        $clientes = Cliente::selectRaw('Clientes.Identificacion, Clientes.ProyectoId, Clientes.municipio_id, cr.ClienteId, SUM(cr.valor) as total_pago, f.SaldoEnMora as mora')
        ->join('ClientesRecaudos as cr', function($join){
            $join->on('cr.ClienteId', '=', 'Clientes.ClienteId')->where('Clientes.EstadoDelServicio', '=', 'Suspendido');
        })
        ->join('ultima_facturaV as uf', 'cr.ClienteId', '=', 'uf.cliente_id')
        ->join('Facturacion as f ', 'uf.factura_id', '=', 'f.FacturaId')
        ->where([
            ['cr.FechaOriginal', '>', $fecha.'-01 00:00:00'], 
            ['f.SaldoEnMora', '>', 0]
        ])
        ->groupBy('Clientes.Identificacion', 'Clientes.ProyectoId', 'Clientes.municipio_id', 'cr.ClienteId', 'f.SaldoEnMora')
        ->get();


        $this->recorrer_clientes($clientes, true);
    }
}
