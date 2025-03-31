<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AcuerdoPago;
use App\CuotaAcuerdoPago;
use App\Recaudo;


class EstadoAcuerdoPago extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'EstadoAcuerdoPago';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar los estados de las cuotas en los acuerdos de pago';

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
        //
        $coutas = CuotaAcuerdoPago::select('cuotas_acuerdo_pago.id','cuotas_acuerdo_pago.valor_pagar','r.valor')
            ->join('acuerdos_pago AS ap', 'cuotas_acuerdo_pago.acuerdo_id', 'ap.id')
            ->join('ClientesRecaudos AS r' , 'ap.cliente_id', 'r.ClienteId')
            ->where([['cuotas_acuerdo_pago.estado','PENDIENTE'],['r.Fecha',date('Y-m-d')]])->get();

        if($coutas->count() > 0){
            foreach ($coutas as $couta ) {
                if($couta->valor >= $couta->valor_pagar){
                    $cuota_P = CuotaAcuerdoPago::find($couta->id);
                    $cuota_P->estado = 'PAGADA';
                    $cuota_P->save();
                }
            }
        }

        $acuerdos = AcuerdoPago::selectRaw('acuerdo_pago.id, acuerdo_pago.total_cuotas, COUNT(cuotas_acuerdo_pago.id) AS cap')
            ->join('cuotas_acuerdo_pago','acuerdos_pago.id','cuotas_acuerdo_pago.acuerdo_id')
            ->where('cuotas_acuerdo_pago.estado','PAGADA')
            ->groupBy(['acuerdo_pago.id','acuerdo_pago.total_cuotas'])
            ->get();
        
        if($acuerdos->count() > 0){
            foreach ($acuerdos as $acuerdo) {
                if($acuerdo->cap == $acuerdo->total_cuotas){
                    $acuerdo_P = AcuerdoPago::find($acuerdo->id);
                    $acuerdo_P->estado = 'FINALIZADO';
                    $acuerdo_P->save();
                }
            }
        }




    }
}
