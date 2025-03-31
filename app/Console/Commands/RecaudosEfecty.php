<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\RecaudoArchivo;
use App\Recaudo;
use App\Cliente;

use DB;

class RecaudosEfecty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RecaudosEfecty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando encargado de conectarse via FTP, Descargar los archivos de salida y sincronizar los recuados diarios';

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
        $directory = base_path('../../../EferctyFTP/Datos');
        $listado_archivos_directorio = File::files($directory);

        $archivos_db = RecaudoArchivo::get()->pluck('archivo')->toArray();

        $carpeta = array();

        //Llenar array con unicamente los nombres de los archivos que se encuentran en la carpeta.
        foreach ($listado_archivos_directorio as $archivo) {
            $carpeta[] = pathinfo($archivo, PATHINFO_BASENAME);
        }

        $faltantes = array_diff($carpeta, $archivos_db);

        $result = DB::transaction(function () use($directory, $faltantes) {

            $contador = 1;

            foreach ($faltantes as $faltante) {
                $archivo = fopen($directory . '/' . $faltante, 'r');

                // Lee el contenido del archivo línea por línea
                while (!feof($archivo)) {

                    //obtenemos la información de la linea y quitamos las comillas dobles.
                    $linea = str_replace('"', '',fgets($archivo));    

                    //Convertimos la información de la linea en un array
                    $contenido = explode("|", $linea);

                    if(count($contenido) > 1 && count($contenido) < 9){
                        DB::rollBack();
                        return ['error', 'Error en la linea ' . $contador . ' del archivo ' . $faltante];
                    }

                    switch ($contenido[0]) {
                        case '02':

                            $cliente_id = Cliente::select('ClienteId')->where('Identificacion', $contenido[1])->first();

                            if(!empty($cliente_id)){

                                //solamente necesitamos la información de las lineas que continen los registros de los clientes
                                $recaudo = new Recaudo;
                                $recaudo->valor = floatval($contenido[2]);
                                $recaudo->Fecha = $contenido[3];
                                $recaudo->cedula = $contenido[1];
                                $recaudo->nombres = $contenido[4];
                                $recaudo->apellido1 = $contenido[5];
                                //$recaudo->apellido2 = $contenido[];
                                $recaudo->campo4 = $contenido[6];
                                //$recaudo->campo5 = $contenido[];
                                $recaudo->ClienteId = $cliente_id->ClienteId;
                                //$recaudo->Periodo = date('Ym');
                                //$recaudo->Referencia = $contenido[];
                                $recaudo->FechaOriginal = date('Y-m-d H:i:s');
                                $recaudo->RecaudadoPor = 'EFECTY';

                                if(!$recaudo->save()){
                                    DB::rollBack();
                                    return ['error', 'Error al guardar la informacion'];
                                }

                            }else{
                                DB::rollBack();
                                return ['error', 'El cliente con cedula '. $contenido[1] . ' no existe.'];
                            }
                            
                            break;                   
                        default:
                            break;
                    }                

                    $contador += 1;

                }
                
                fclose($archivo);

                //Guardamos el nombre del archivo en la tabla correspondiente para no volverlo a listar.
                $recaudo_archivo = new RecaudoArchivo;
                $recaudo_archivo->archivo = $faltante;

                if(!$recaudo_archivo->save()){                
                    DB::rollBack();
                    return ['error', 'Error al guardar el archivo ' . $faltante];
                }
            }

            return ['success', 'Archivos guardado correctamente.'];


        });

        if($result[0] == 'success'){
            $this->info($result[0] . ' ' . $result[1]);

        }else{
            $this->error($result[0] . ' ' . $result[1]);
        }
        
    }
}
