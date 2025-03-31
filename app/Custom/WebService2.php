<?php 

namespace App\Custom;

use Illuminate\Support\Facades\Crypt;
use App\Olt;
use App\Cliente;
use App\ProyectoMunicipio;
use Firebase\JWT\JWT;



class WebService2
{
   

    public function listar_cliente($municipio_id){

        $resultado = array();
        $token = $this->creartoken();

        $clientes = Cliente::
                    select( 'metas_clientes.idpunto',
                            'Clientes.ClienteId',
                            'Clientes.Identificacion',
                            'Clientes.Latitud',
                            'Clientes.Longitud',
                            'ActivosFijos.Serial',
                            'Clientes.municipio_id',
                            'Municipios.CodigoDane',
                            'Departamentos.CodigoDaneDepartamento',
                            'Clientes.Status'
                          )
                    ->join('metas_clientes','Clientes.ClienteId', '=', 'metas_clientes.ClienteId')
                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->join('Departamentos', 'Municipios.DeptId', '=', 'Departamentos.DeptId')
                    ->join('clientes_onts_olts','Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
                    ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
                    ->leftJoin('clientes_reemplazos', 'metas_clientes.id', '=','clientes_reemplazos.meta_cliente_id')
                    ->whereNull('clientes_reemplazos.meta_cliente_id')
                    ->where([['Clientes.Status', 'ACTIVO'],['Clientes.municipio_id', $municipio_id]])
                    ->get();



        return $clientes;

       $result = $this->estado_cliente_olt($clientes, $municipio_id);

       

       if(is_array($result[0])){         
           foreach ($result as $key => $value) {
               foreach ($value as $data) {

                    $estado = '';

                    switch ($data['ont']['Control flag']) {
                        case 'active':
                            if ($data['ont']['Run state '] == 'offline') {
                                $estado = 'i';
                            }else if($data['ont']['Run state '] == 'online'){
                                $estado = 'A';
                            }

                            break;
                        
                        case 'deactivated':
                            if ($data['estado'] == 'INACTIVO') {
                                $estado = 'I';
                            }else{
                                $estado = 'i';
                            }
                            
                            break;
                    }

                    $datos = [
                        "operador_cod" => "operinfrahg_13",
                        "id_Beneficiario" => $data["id-punto"],                            
                        "codDaneMuni" => $data["dane-municipio"],
                        "codDaneDepar" => $data["dane-departamento"],
                        "longitud" => $data["longitud"],
                        "latitud" => $data["latitud"],
                        "estado" => $estado,
                        "velocidadSub" => "1024kbps",
                        "velocidadBaj" => "5120kbps"];

                    #DESCONECTAR PARA ENVIAR A MINTIC
                    $result = str_replace("\r\n\t", "", $this->sendClient($token, json_encode($datos)));

                    #DESCOMENTAR PARA PRUEBAS
                    //$resultado[] = $datos;

                    #DESCOENTAR PARA ENVIAR A MINTIC
                   $resultado[] = json_decode(str_replace("'", '"',$result));
               }
           }
       }else{
            abort(500);
       }
             

        return $resultado;
    }

    private function estado_cliente_olt($accesos, $municipio){


        $olt = Olt::where('municipio_id', $municipio)->first();

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

            foreach ($accesos as $cliente) {
                $j = $j+1;
                #para cada uno de los clientes se debe ejecutar estado

                $dato = array();

                $dato[$cliente->ClienteId] = array('cedula' => $cliente->Identificacion,
                                                    'id-punto' => str_replace("-", "",$cliente->idpunto),
                                                    'latitud' => $cliente->Latitud,
                                                    'longitud' => $cliente->Longitud,
                                                    'dane-municipio' => $cliente->CodigoDane,
                                                    'dane-departamento' => $cliente->CodigoDaneDepartamento,
                                                    'estado' => $cliente->Status,
                                                    'ont' => ''
                                                  );

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

                            default:
                                    
                                    break;

                        }                       

                    }

                    $dato[$cliente->ClienteId]['ont'] = $ont;

                }else{
                    break;
                }

                $datos[] = $dato;        
                
            #fin
            }

            $client->disconnect();                          

        }catch (\Exception $e) {
            $datos[] = $e->getMessage();
        }

        return $datos;

    }

    private function creartoken(){
        
        $time = time();
        $key = 'mzHc21!20-CM4.M1n71C';

        $token = array(
            'user_id' => 'operinfrahg_13',
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (30*30), // Tiempo que expirará el token (15 minutos)           
            'iss' => 'localhost'
        );

        $jwt = new \Firebase\JWT\JWT;

        $jwt = JWT::encode($token, $key);
        //$data = JWT::decode($jwt, $key, array('HS256'));

        return $jwt;
    }

    private function sendClient($token, $datos_cliente){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        #PRODUCCION
        //https://apicma.mintic.gov.co/ws_cma/ingresoPunto/

        #PRUEBA
        //https://misiontic2022.mintic.gov.co/ws_cma/ingresoPunto/
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://apicma.mintic.gov.co/ws_cma/ingresoPunto/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $datos_cliente,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token,
            'Content-Type: text/plain'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }      
}