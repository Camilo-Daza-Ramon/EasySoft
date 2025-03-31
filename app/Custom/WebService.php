<?php 

namespace App\Custom;

use Illuminate\Support\Facades\Crypt;
use App\Olt;
use App\Cliente;
use App\ProyectoMunicipio;
use Firebase\JWT\JWT;



class WebService
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

        //return $clientes;

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

    public function clientes_sin_onts(){

        $resultado = array();
        $token = $this->creartoken();

        $clientes = Cliente::
                    select( 'metas_clientes.idpunto',
                            'Clientes.ClienteId',
                            'Clientes.Identificacion',
                            'Clientes.Latitud',
                            'Clientes.Longitud',
                            'Clientes.municipio_id',
                            'Municipios.CodigoDane',
                            'Departamentos.CodigoDaneDepartamento',
                            'Clientes.Status'
                          )
                    ->join('metas_clientes','Clientes.ClienteId', '=', 'metas_clientes.ClienteId')
                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->join('Departamentos', 'Municipios.DeptId', '=', 'Departamentos.DeptId')
                    ->leftJoin('clientes_onts_olts', 'Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
                   ->leftJoin('clientes_reemplazos', 'metas_clientes.id', '=','clientes_reemplazos.meta_cliente_id')
                        ->whereNull('clientes_reemplazos.meta_cliente_id')
                    ->whereNull('clientes_onts_olts.ClienteId')                    
                    ->get();

        //return $clientes;

       foreach ($clientes as $cliente) {

            $datos = [
                "operador_cod" => "operinfrahg_13",
                "id_Beneficiario" => str_replace("-", "",$cliente->idpunto),
                "codDaneMuni" => $cliente->CodigoDane,
                "codDaneDepar" => $cliente->CodigoDaneDepartamento,
                "longitud" => $cliente->Longitud,
                "latitud" => $cliente->Latitud,
                "estado" => 'A',
                "velocidadSub" => "1024kbps",
                "velocidadBaj" => "5120kbps"];

                $result = str_replace("\r\n\t", "", $this->sendClient($token, json_encode($datos)));

           $resultado[] = json_decode(str_replace("'", '"',$result));
       }
           
       

       //$resultado['el_token'] = $token;

       

        return $resultado;
    }

    public function clientes_no_activos(){

        $resultado = array();
        $token = $this->creartoken();
        
        $clientes = Cliente::
                    select( 'metas_clientes.idpunto',
                            'Clientes.ClienteId',
                            'Clientes.Identificacion',
                            'Clientes.Latitud',
                            'Clientes.Longitud',
                            'Clientes.municipio_id',
                            'Municipios.CodigoDane',
                            'Departamentos.CodigoDaneDepartamento',
                            'Clientes.Status'
                          )
                    ->join('metas_clientes','Clientes.ClienteId', '=', 'metas_clientes.ClienteId')
                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                    ->join('Departamentos', 'Municipios.DeptId', '=', 'Departamentos.DeptId')
                    ->join('clientes_onts_olts', 'Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
                    ->leftJoin('clientes_reemplazos', 'metas_clientes.id', '=','clientes_reemplazos.meta_cliente_id')
                        ->whereNull('clientes_reemplazos.meta_cliente_id')
                    ->whereNotIn('Clientes.Status', ['ACTIVO'])
                    ->get();

        //return $clientes;

       foreach ($clientes as $cliente) {

            $datos = [
                "operador_cod" => "operinfrahg_13",
                "id_Beneficiario" => str_replace("-", "",$cliente->idpunto),
                "codDaneMuni" => $cliente->CodigoDane,
                "codDaneDepar" => $cliente->CodigoDaneDepartamento,
                "longitud" => $cliente->Longitud,
                "latitud" => $cliente->Latitud,
                "estado" => 'A',
                "velocidadSub" => "1024kbps",
                "velocidadBaj" => "5120kbps"];

                $result = str_replace("\r\n\t", "", $this->sendClient($token, json_encode($datos)));

           $resultado[] = json_decode(str_replace("'", '"',$result));
       }
           
       

       //$resultado['el_token'] = $token;

       

        return $resultado;
    }

    public function clientes_reemplazados(){

        $municipios_proyecto = Cliente::select('Clientes.municipio_id')
                        ->join('clientes_onts_olts', 'Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
                        ->join('clientes_reemplazos', 'Clientes.ClienteId', '=','clientes_reemplazos.cliente_nuevo_id')
                        ->join('metas_clientes','clientes_reemplazos.meta_cliente_id', '=', 'metas_clientes.id')                    
                        ->where('Clientes.Status', 'ACTIVO')
                        ->groupBy('Clientes.municipio_id')
                        ->get();

        $resultado = array();
        $token = $this->creartoken();

        foreach ($municipios_proyecto as $municipio) {
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
                        ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')
                        ->join('Departamentos', 'Municipios.DeptId', '=', 'Departamentos.DeptId')
                        ->join('clientes_onts_olts','Clientes.ClienteId', '=', 'clientes_onts_olts.ClienteId')
                        ->join('ActivosFijos', 'clientes_onts_olts.ActivoFijoId', '=', 'ActivosFijos.ActivoFijoId')
                        ->join('clientes_reemplazos', 'Clientes.ClienteId', '=','clientes_reemplazos.cliente_nuevo_id')
                        ->join('metas_clientes','clientes_reemplazos.meta_cliente_id', '=', 'metas_clientes.id')                    
                        ->where([['Clientes.Status', 'ACTIVO'],['Clientes.municipio_id', $municipio->municipio_id]])
                        ->get();

           $result = $this->estado_cliente_olt($clientes, $municipio->municipio_id);

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

                            $result = str_replace("\r\n\t", "", $this->sendClient($token, json_encode($datos)));



                       $resultado[] = json_decode(str_replace("'", '"',$result));
                   }
               }
           }else{
                continue;
           }   

       }

       //$resultado['el_token'] = $token;

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


    /*-----------------------------------------ON FUNCIONÓ--------------------------------------------------------*/

    public function listado_olts(){
        $olts = Olt::where([['estado', 'ACTIVO'], ['id', 5]])->get();

        $result = $this->listar_puertos();//$this->listar_tarjetas($olts);

        return $result;
    }

    private function listar_tarjetas($olts){
        
        $tarjetas = array();

        foreach ($olts as $olt){

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

                $command = 'display board 0';
                $resp = $client->execute($command,'#');

                $client->clearBuffer(); 
                $client->disconnect();

                $response = explode("\r\n", $resp);
                $tarjetas[$olt->id] = $this->convertir_array($response,1);
 
                /*for ($i=2; $i < count($response); $i++) { 
                    $datos = explode(":", $response[$i]);
                    $tarjetas[str_replace('  ', '', $datos[0])] = $datos[1];                                      
                }*/

                
                       

            }catch (\Exception $e) {
                $error[] = $e->getMessage();
            }
        }

        return $tarjetas;
    }

    private function listar_puertos(){

        $result = "";

        //foreach($tarjetas as $id => $tarjeta){
            //if($tarjeta['BoardName'] == 'H807GPBH'){

                $olt = Olt::findOrFail(6);

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

                    //$respuestas = array();
                    //foreach($tarjeta as $dato){
                        $command = "interface gpon 0/". 0;
                        $client->execute($command);

                        //for ($i=0; $i < 2; $i++) {
                            $command = "display port state 0";
                            $respuesta = $client->execute($command);
                       // }
                        
                        $client->execute('q');
                    //}
                   

                    $client->clearBuffer(); 
                    $client->disconnect();

                    //$respuesta = explode("\r\n", $respuesta);
                    return $respuesta;
                    $result = $this->convertir_array($respuesta,12);

                    /*foreach($respuestas as $respuesta){
                        $respuesta = explode("\r\n", $respuesta);
                        $puertos[$olt->id][] = $this->convertir_array($respuesta);
                    }*/
     
                    /*for ($i=2; $i < count($response); $i++) { 
                        $datos = explode(":", $response[$i]);
                        $tarjetas[str_replace('  ', '', $datos[0])] = $datos[1];                                      
                    }*/
                }catch (\Exception $e) {
                    $error[] = $e->getMessage();
                }

                return $result;

            //}
        //}
    }

    private function convertir_array($data,$inicio){
    
        $encabezado = 2;
        $header = array();

        $result = array();
        for ($i=$inicio; $i < count($data); $i++) {
            
           if(substr($data[$i], 0,7) == '  -----'){
               //echo "SI\n";
               $encabezado -=1;
               continue;
            }else{
                if($encabezado > 0){
                   $datos = explode("  ", $data[$i]);
                   foreach ($datos as $dato){
                       if(!empty($dato)){
                           #obtenemos el nombre de las columnas
                           $header[] = $dato;
                       }
                   }                   
                }else{
                    $array_data1 = explode("  ", $data[$i]);
                    $datos2 = array();
                    
                    
                    foreach ($array_data1 as $dato2){
                       if(strlen($dato2) > 0){
                           #obtenemos el nombre de las columnas
                           $datos2[] = $dato2;
                       }
                   }
                    
                    $array_data = array();
                    
                    for ($j = 0; $j < count($header); $j++) {
                        
                        if(!isset($datos2[$j])){
                            $array_data[$header[$j]] = '';
                        }else{
                            if(strlen($datos2[$j]) > 0){
                                
                               #obtenemos el nombre de las columnas
                                $array_data[$header[$j]] = str_replace(' ', '',$datos2[$j]);
                            }
                        }
                   }                    
                    
                    $result[] = $array_data;
                }
           }
        }

        return $result;          
    }    
}