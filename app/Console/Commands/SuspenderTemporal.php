<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\ONT;
use App\Novedad;
use App\SuspensionTemporal;
use DB;

class SuspenderTemporal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SuspensionTemporal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recorre la información de la tabla de suspensiones con el fin de iniciar con el proceso de suspensión temporal de los clientes que lo solicitaron';

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
        $suspensiones_temporales = SuspensionTemporal::where([
            ['estado', 'PENDIENTE'],
            ['fecha_hora_inicio', '<=', date('Y-m-d H:i:s')],
        ])->get();

        if($suspensiones_temporales->count() > 0){

            $result = DB::transaction(function () use($suspensiones_temporales) {

                foreach ($suspensiones_temporales as $suspension_temporal) {

                    $olt = $suspension_temporal->cliente->cliente_ont_olt->olt;
                    $serial = $suspension_temporal->cliente->cliente_ont_olt->activo->Serial;

                    $ip = $olt->ip;
                    $usuario = $olt->usuario;
                    $pass = $olt->password;                    

                    $cliente = $suspension_temporal->cliente;
                    $cliente->EstadoDelServicio = 'Suspendido';

                    if(!$cliente->save()){
                        DB::rollBack();
                        return ['error', 'Error al actualizar el estado del servicio del cliente.'];
                    }

                    $ont = new ONT($ip, $usuario, $pass);

                    $resultado = $ont->ejecutar($olt->version, $serial, $cliente, 'Suspendido');

                    //dd($resultado);

                    if($resultado[0] == 'success'){
                        //Creamos la novedad para que se descuente los valores correspondientes en la factura.

                        $novedad = new Novedad();
                        $novedad->concepto = 'Suspensión Temporal';
                        $novedad->fecha_inicio = $suspension_temporal->fecha_hora_inicio;
                        $novedad->fecha_fin = $suspension_temporal->fecha_hora_fin;

                        $novedad->estado = 'PENDIENTE';
                        $novedad->cobrar = false;
                        $novedad->unidad_medida = 'MINUTOS';
                        $novedad->user_id = 1;
                        $novedad->ClienteId = $suspension_temporal->cliente_id;

                        if ($novedad->save()) {

                            $suspension_temporal->novedad_id = $novedad->id;
                            $suspension_temporal->estado = 'ACTIVA';

                            if($suspension_temporal->save()){
                                return ['success', 'Suspensión temporal aplicada correctamente.'];
                            }else{
                                DB::rollBack();
                                return ['error', 'Error al relacionar la novedad con la suspensión'];
                            }

                        }else{
                            DB::rollBack();
                            return ['error', 'No se logró crear la novedad.'];
                        }

                    }else{
                        DB::rollBack();
                        return $resultado;
                    }
                }
            });

            dd($result);
        }
        
    }
}
