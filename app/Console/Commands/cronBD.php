<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

use App\Citas;
use App\Agendas;

class cronBD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CitasVencidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de todas las citas que ya estan vencidas';

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
        //Actualizamos todas las citas donde la fecha sea menor a la actual

        $citas = Citas::where([['estado', '=', 'ASIGNADA'], ['fecha_cita', '<' , date('Y-m-d')]])
        ->update(['estado' => 'VENCIDA']);

        $agenda = Agendas::where([['estado', '=', 'DISPONIBLE'], ['fecha_disponible', '<=' , date('Y-m-d')]])
        ->update(['estado' => 'VENCIDA']);
    }
}
