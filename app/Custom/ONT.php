<?php

namespace App\Custom;
use Illuminate\Support\Facades\Crypt;
use App\ContratoEvento;
use App\User;


class ONT
{
    protected $ip;
    protected $usuario;
    protected $password;

    public function __construct($ip, $usuario, $password) {
        $this->ip = $ip;
        $this->usuario = $usuario;
        $this->password = Crypt::decrypt($password);
    }


    public function ejecutar($version, $serial, $cliente, $estado, $user_id = null){

        $resultado = $this->actualizar_contrato_servicios($cliente, $estado, $user_id);

        if($resultado[0] == 'success'){

            try {

                switch ($estado) {
                    case 'Activo':
                        $estado = 'activate';
                        break;
                    case 'Suspendido':
                        $estado = 'deactivate';
                        break;
                    
                    default:
                        return ['error', 'No se permite esta acción.'];
                        break;
                }

                #Informacion ONT
                $client = new \Bestnetwork\Telnet\TelnetClient($this->ip);
                $client->login($this->usuario, $this->password);

                $command = 'enable';
                $client->execute($command);

                $command = 'config';
                $client->execute($command);

                $resp = "";
                $response = "";

                $i = 2;

                $command = 'display ont info by-sn ' . $serial;

                if($version == 2){
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

                    $command = 'ont '.$estado.' '. $fsp[2] .' ' . $ontid;
                    $client->execute($command);
                    

                    $client->execute('q');
                    $client->clearBuffer();

                    $client->disconnect();                

                    return ['success', 'Accion realizada  correctamente.'];

                }else{                
                    return ['error', 'No se pudo acceder a la ONT.'];
                }

            }catch (\Exception $e) {
                return $e->getMessage();
                return ['error', 'No se pudo activar al cliente.'];
            }

        }else{
            return $resultado;
        }
    }

    private function actualizar_contrato_servicios($cliente, $estado, $user_id = null){

        $contratos = $cliente->contrato->where('estado', 'VIGENTE');

        if($contratos->count() > 0){

            foreach ($contratos as $contrato) {

                $servicios = $contrato->servicio;

                foreach($servicios as $servicio){

                    $servicio->estado = $estado;

                    if($servicio->save()){

                        $evento = new ContratoEvento;
                        $evento->accion = "Suspension";

                        if(!empty($user_id)){

                            $user = User::find($user_id);
                            
                            $evento->descripcion = "Servicio #". $servicio->id ." $estado por $user->name. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;

                        }else{
                            $evento->descripcion = "Servicio #". $servicio->id ." $estado por el sistema. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;

                        }


                        $evento->descripcion = "Servicio #". $servicio->id ." $estado por el sistema. Nombre:" . $servicio->nombre . ", Descripcion:" . $servicio->descripcion . " valor:". $servicio->valor;
                        $evento->contrato_id = $servicio->contrato_id;

                        if ($evento->save()) {                            
                            return ['success', 'Contrato servicio actualizado'];
                        }else{
                            return ['error', 'Error al crear el evento.'];
                        }

                    }else{                       
                        return ['error', 'Error al actualizar el estado del servicio.'];
                    }
                }
            }

        }else{
            DB::rollBack();
            return ['error', 'Error no tiene contratos'];
        }
    }
    

}