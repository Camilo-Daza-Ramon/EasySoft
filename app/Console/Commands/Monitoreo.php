<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use App\Olt;
use App\Cliente;
use App\MonitoreoCliente;
use DB;
use Carbon\Carbon;

class Monitoreo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoreo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando que ingresa a la ONT de cada cliente y guarda la informacion con el fin de identificar los clientes que ´llevan tiempo sin navegar a internet y no han reportado nada en el sistema.';

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
        $clientes = Cliente::select('Clientes.ClienteId','ActivosFijos.Serial', 'Clientes.municipio_id')
        ->join('clientes_onts_olts','Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
        ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
        ->where([['Clientes.Status', 'ACTIVO'], ['municipio_id', 996]])
        //->whereIn('Clientes.Identificacion', [65731799])
        ->get();


        $olt = Olt::where('municipio_id', 996)->first();

        $error = array();

        $datos = array();

        
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

            $j = 0;

            foreach ($clientes as $cliente) {
                $j = $j+1;
                #para cada uno de los clientes se debe ejecutar estado

                $dato = array();                    

                $resp = "";
                $response = "";

                $i = 2;
                $command = 'display ont info by-sn '. $cliente->Serial;

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

                    $monitoreo = MonitoreoCliente::where('cliente_id',$cliente->ClienteId)->first();

                    if (count($monitoreo) == 0) {
                        $monitoreo = new MonitoreoCliente;
                    }

                    for ($i; $i < count($response); $i++) {
                        $data = explode(" : ", $response[$i]);

                        $key = str_replace('  ', '', $data[0]);

                        switch ($key) {
                            case 'F/S/P':
                                $monitoreo->fsp = str_replace(' ', '', $data[1]);
                                break;

                            case 'ONT-ID ':
                                $monitoreo->ont_id = str_replace(' ', '', $data[1]);
                                break;

                            case 'Control flag ':
                                $monitoreo->control_flag = str_replace(' ', '', $data[1]);
                                break;
                            
                            case 'Run state':
                                $monitoreo->run_state = str_replace(' ', '', $data[1]);
                                break;

                            case 'Config state ':
                                $monitoreo->config_state = str_replace(' ', '', $data[1]);
                                break;

                            case 'Match state':
                                $monitoreo->match_state = str_replace(' ', '', $data[1]);
                                break;

                            case 'Last down cause':
                                $monitoreo->last_down_cause = str_replace(' ', '', $data[1]);
                                break;

                            case 'Last up time ':
                                $monitoreo->last_up_time = $data[1];
                                break;

                            case 'Last down time ':
                                $monitoreo->last_down_time = $data[1];
                                break;

                            case 'Last dying gasp time ':
                                $monitoreo->last_dying_gasp_time = $data[1];
                                break;

                            default:

                            break;

                        }
                    }

                    $monitoreo->ultima_ejecucion_script = date('Y-m-d');
                    $monitoreo->cliente_id = $cliente->ClienteId;
                    $monitoreo->save();

                    $dato[$cliente->ClienteId]['ont'] = $ont;

                }else{
                    break;
                }        
                
            #fin
            }

            $client->disconnect();                          

        }catch (\Exception $e) {
            $datos[] = $e->getMessage();
        }


    }
}
