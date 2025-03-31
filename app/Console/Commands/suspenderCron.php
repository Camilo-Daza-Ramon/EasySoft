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
use App\ReporteOntFallida;
use Bestnetwork\Telnet\TelnetException;
use DB;
use Carbon\Carbon;
use Exception;

class suspenderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspenderClientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspender de forma masiva todos los clientes que al primero de cada mes no se haya puesto al día en el pago de su factura.';

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

        //13746039,12524357,1065245880
        //1064110104,1064112129,

        $municipios = Cliente::select('Clientes.municipio_id')
        ->join('clientes_onts_olts','Clientes.ClienteId','=','clientes_onts_olts.ClienteId')
        ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
        //->join('PlanesComerciales', 'Clientes.PlanComercial','=','PlanesComerciales.PlanId')
        ->join('historial_factura_pagoV', 'Clientes.ClienteId','=','historial_factura_pagoV.ClienteId')
        ->where([
            ['Clientes.Status','ACTIVO'], 
            ['EstadoDelServicio', 'Activo'],
        ])
        //->whereIn('Clientes.Identificacion', [78588571, 1002995903])
        ->whereRaw('historial_factura_pagoV.total_deuda > 200')
        //->whereRaw('historial_factura_pagoV.total_deuda >= (PlanesComerciales.ValorDelServicio * 3)')
        ->whereIn('Clientes.municipio_id', [406,407,420])//Valledupar, Aguachica, La jagua de ibirico
        ->groupBy('Clientes.municipio_id')
        ->get();
        
        if (count($municipios) > 0) {

            $result = array();

            foreach ($municipios as $municipio) {

                $olt = Olt::where('municipio_id', $municipio->municipio_id)->first();

                $error = array();
                
                try { 
                    $usuario = $olt->usuario;
                    $pass = Crypt::decrypt($olt->password); 
                    $ip = $olt->ip;
                    
                    #Informacion ONT
                    $client = new \Bestnetwork\Telnet\TelnetClient($ip);
                    $client->login($usuario, $pass);

                    $command = 'enable';
                    $client->execute($command);

                    $command = 'config';
                    $client->execute($command);

                    $n = 0;

                    $clientes = Cliente::select('Clientes.ClienteId', 'Clientes.Identificacion', 'ActivosFijos.Serial', 'Clientes.municipio_id')
                    ->join('clientes_onts_olts','Clientes.ClienteId','=','clientes_onts_olts.ClienteId')
                    ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
                    //->join('PlanesComerciales', 'Clientes.PlanComercial','=','PlanesComerciales.PlanId')
                    ->join('historial_factura_pagoV', 'Clientes.ClienteId','=','historial_factura_pagoV.ClienteId')
                    ->where([['Clientes.Status','ACTIVO'], ['EstadoDelServicio', 'Activo'],['Clientes.municipio_id', $municipio->municipio_id]])
                    //->whereIn('Clientes.Identificacion', [78588571, 1002995903])
                    ->whereRaw('historial_factura_pagoV.total_deuda > 200')
                    //->whereRaw('historial_factura_pagoV.total_deuda >= (PlanesComerciales.ValorDelServicio * 3)')
                    ->get();
                    
                    foreach ($clientes as $cliente_suspender) {

                        $n = $n+1;
                        #para cada uno de los clientes se debe ejecutar estado

                        $resp = "";
                        $response = "";
                        $i = 2;
                        try {
                            $command = 'display ont info by-sn ' . $cliente_suspender->Serial;

                            if($olt->version == 2){
                                $i = 3;
                                $client->execute($command, ":");
                                $resp = $client->execute("\r\n", "---- More ( Press 'Q' to break ) ----");
                            }else{
                                $resp = $client->execute($command, "---- More ( Press 'Q' to break ) ----");
                            }
                            
                        } catch (TelnetException $th) {
                            echo "Error: " . $th->getMessage();
                            
                            $posibleReporte = ReporteOntFallida::where('ONT_Serial', '=', $cliente_suspender->Serial)
                                ->where('ClienteId', '=', $cliente_suspender->ClienteId);

                            if (!$posibleReporte->exists()) {
                                $reporte = new ReporteOntFallida();
                                $reporte->ClienteId = $cliente_suspender->ClienteId;
                                $reporte->ONT_Serial = $cliente_suspender->Serial;
                                $reporte->Identificacion = $cliente_suspender->Identificacion;
    
                                $mensaje = $th->getMessage();
                                if (strpos($th->getMessage(), "The required ONT does not exist") !== null) {
                                    $mensaje = "La ONT requerida no existe";
                                }
                                
                                $reporte->mensaje = $mensaje;
                                $reporte->save();
                            } elseif ($posibleReporte->first()->mensaje !== 'La ONT requerida no existe'
                                && $th->getMessage() !== $posibleReporte->first()->mensaje) {
                                $reporte = ReporteOntFallida::where('ONT_Serial', '=', $cliente_suspender->Serial)->first();
                                $reporte->mensaje .= "\r\n" . $th->getMessage();
                                $reporte->save();
                            } 

                            continue;
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

                            $command = 'ont deactivate '. $fsp[2] .' ' . $ontid;
                            $client->execute($command);
                            

                            $client->execute('q');
                            $client->clearBuffer();

                            $novedad_pendiente = Novedad::where('ClienteId', $cliente_suspender->ClienteId)
                            ->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora'])
                            ->whereNull('fecha_fin')
                            ->first();
                            
                            $result = DB::transaction(function () use($cliente_suspender, $novedad_pendiente) {
                                $cliente = Cliente::find($cliente_suspender->ClienteId);
                                $cliente->EstadoDelServicio = 'Suspendido';

                                if ($cliente->save()) {

                                    $contrato = ClienteContrato::select('contratos_servicios.id')                    
                                    ->join('contratos_servicios','clientes_contratos.id', '=', 'contratos_servicios.contrato_id')
                                    ->whereNotIn('clientes_contratos.estado', ['FINALIZADO','ANULADO','PENDIENTE'])
                                    ->whereNotIn('contratos_servicios.estado', ['Inactivo','Suspendido'])
                                    ->where('clientes_contratos.ClienteId', $cliente->ClienteId)
                                    ->get();

                                    if ($contrato->count() > 0) {
                                        foreach ($contrato as $servicios) {
                                            $servicio = ContratoServicio::find($servicios->id);
                                            $servicio->estado = 'Suspendido';

                                            if ($servicio->save()) {                                    
                                                #Registro de Evento
                                                $evento = new ContratoEvento;
                                                $evento->accion = "Suspension";
                                                $evento->descripcion = "Servicio #". $servicio->id ." suspendido por el sistema. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;
                                                $evento->contrato_id = $servicio->contrato_id;

                                                if (!$evento->save()) {
                                                    DB::rollBack();
                                                    return ['error', 'No se logró crear el registro del evento.'];
                                                }else{
                                                    if (empty($novedad_pendiente)) {
                                                        $novedad = new Novedad();
                                                        $novedad->concepto = 'Suspensión por Mora';
                                                        $novedad->fecha_inicio = date('Y-m-d H:i:s');
                                                        $novedad->estado = 'PENDIENTE';
                                                        $novedad->cobrar = false;
                                                        $novedad->unidad_medida = 'MINUTOS';
                                                        $novedad->user_id = 1;
                                                        $novedad->ClienteId = $cliente->ClienteId;

                                                        if (!$novedad->save()) {
                                                            DB::rollBack();
                                                            return ['error', 'No se logró crear la novedad.'];
                                                        }else{
                                                            //$suspendido = new ClienteSuspension;
                                                            //$suspendido->tipo = 'MORA';
                                                            //$suspendido->fecha_inicio = date('Y-m-d H:i:s');
                                                            //$suspendido->user_id = 1;
                                                            //$suspendido->cliente_id = $cliente->ClienteId;
                                                        }

                                                        //if (!$suspendido->save()) {
                                                            //DB::rollBack();
                                                            //return ['error', 'No se logró agregar la informacion a la tabla de suspensiones.'];
                                                        //}
                                                    }
                                                }
                                            }else{
                                                DB::rollBack();
                                                return ['error', 'No se logró actualizar el servicio.'];
                                            }
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
                    }

                    $client->disconnect();                          

                }catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }
                
            }
        }        
    }
}


//C:\Program Files (x86)\PHP\v5.6\php.exe -f "D:\Awebsites\ConstruyendoWebSite\easy\artisan" suspenderClientes