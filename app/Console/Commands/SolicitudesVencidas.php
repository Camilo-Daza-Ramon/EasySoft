<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Solicitud;

class SolicitudesVencidas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SolicitudesVencidas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de las solicitudes a VENCIDA cuya fecha limite se ha cumplido.';

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
        $solicitudes_vencidas = Solicitud::where([
            ['estado', 'PENDIENTE'],
            ['fecha_limite', '<', date('Y-m-d')]
        ]);

        if($solicitudes_vencidas->count() > 0){
            $solicitudes_vencidas->update(['estado' => 'VENCIDA']);            
        }
    }
}
