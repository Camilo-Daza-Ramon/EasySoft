<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Agentes;
use App\Citas;
use App\Asignaciones;

class AsignarRecordacion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asignarRecordacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consiste en asignar a todos los agentes que se encuentren activos las citas que estan pendientes por llamar para recordarle a los usuarios la asistencia de la misma.';

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
        //Asignaciones::where('estado', 'PENDIENTE')->delete();

        $agentes = Agentes::select('id')->where('estado', 'ACTIVO')->get();

        $total_citas = Citas::select('id')->where('fecha_cita', '>' , date('Y-m-d'))->whereNull('estado_llamada')->count();

        $total_registros = round( count($agentes) / $total_citas);


        foreach ($agentes as $agente) {
            # code...
            $citas = Citas::select('id')->where('fecha_cita', '>' , date('Y-m-d'))->whereNull('estado_llamada')->limit($total_registros)->get();

            foreach ($citas as $cita) {

                $actualizar_cita = Citas::find($cita->id);
                $actualizar_cita->estado_llamada = 'ASIGNADA';
                $actualizar_cita->save();

                $asignacion = new Asignaciones;
                $asignacion->tipo = 'RECORDACION';
                $asignacion->estado = 'PENDIENTE';
                $asignacion->cita_id = $cita->id;
                $asignacion->agente_id = $agente->id;
                $asignacion->save();
            }
        }
    }
}
