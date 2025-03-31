<?php 
namespace App\Custom;

use Illuminate\Support\Facades\Crypt;
use App\Cliente;
use App\Olt;
use App\ClienteContrato;
use App\ContratoServicio;
use App\ContratoEvento;
use App\Novedad;
use DB;
use Carbon\Carbon;

/**
 * 
 */
class Suspender
{
    public function generar_manual($cedulas){

		$municipios = $this->municipios($cedulas);

        if (count($municipios) > 0) {

            $result = array();

            foreach ($municipios as $municipio) {

                $olt = Olt::where('municipio_id', $municipio->municipio_id)->first();

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


                    $clientes = $this->clientes;

                    foreach ($clientes as $cliente_suspender) {
                    	









                        $n = $n+1;
                        #para cada uno de los clientes se debe ejecutar estado

                        $resp = "";
                        $response = "";

                        $i = 2;

                        $command = 'display ont info by-sn ' . $cliente_suspender->Serial;

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

                            $command = 'ont deactivate '. $fsp[2] .' ' . $ontid;
                            $client->execute($command);
                            

                            $client->execute('q');
                            $client->clearBuffer();

                            $novedad_pendiente = Novedad::where('ClienteId', $cliente_suspender->ClienteId)
                            ->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora'])
                            ->whereNull('fecha_fin')
                            ->first();                            
                        }else{
                            break;
                        }                        
                        
                    #fin
                    }

                    $client->disconnect();                          

                }catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }

                dd(array($error,$result));
                
            }
        }
	}

	private function municipios($cedulas){

		$municipios = Cliente::select('Clientes.municipio_id')
        ->join('clientes_onts_olts','Clientes.ClienteId','=','clientes_onts_olts.ClienteId')
        ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
        //->join('PlanesComerciales', 'Clientes.PlanComercial','=','PlanesComerciales.PlanId')
        ->join('historial_factura_pagoV', 'Clientes.ClienteId','=','historial_factura_pagoV.ClienteId')
        //->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
        ->where([['Clientes.Status','ACTIVO'], ['EstadoDelServicio', 'Activo']])
        ->whereIn('Clientes.Identificacion', [$cedulas])
        //->whereRaw('historial_factura_pagoV.total_deuda >= (PlanesComerciales.ValorDelServicio * 3)')
        ->groupBy('Clientes.municipio_id')
        ->get();

        return $municipios;

	}

	private function clientes($cedulas){

		$clientes = Cliente::select('Clientes.ClienteId', 'Clientes.Identificacion', 'ActivosFijos.Serial', 'Clientes.municipio_id')
                    ->join('clientes_onts_olts','Clientes.ClienteId','=','clientes_onts_olts.ClienteId')
                    ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
                    //->join('PlanesComerciales', 'Clientes.PlanComercial','=','PlanesComerciales.PlanId')
                    ->join('historial_factura_pagoV', 'Clientes.ClienteId','=','historial_factura_pagoV.ClienteId')
                    //->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->where([['Clientes.Status','ACTIVO'], ['EstadoDelServicio', 'Activo'],['Clientes.municipio_id', $municipio->municipio_id]])
                    ->whereIn('Clientes.Identificacion', [$cedulas])
                    //->whereRaw('historial_factura_pagoV.total_deuda >= (PlanesComerciales.ValorDelServicio * 3)')
                    ->get();

        return $clientes;

	}
}


?>