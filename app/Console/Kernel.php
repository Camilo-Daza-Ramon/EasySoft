<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\suspenderCron::class,
        \App\Console\Commands\MinTIC::class,
        \App\Console\Commands\Monitoreo::class,
        \App\Console\Commands\ActivacionesMasivas::class,
        \App\Console\Commands\SolicitudesVencidas::class,
        \App\Console\Commands\EstadoAcuerdoPago::class,
        \App\Console\Commands\RecaudosEfecty::class,
        \App\Console\Commands\SuspenderTemporal::class,        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('suspenderClientes')
                  ->monthlyOn(6, '03:00');
        $schedule->command('reportarMinTIC')
                  ->hourly(3, '17:00');
        $schedule->command('suspenderClientes')
                  ->daily(1, '03:00');
        $schedule->command('ActivacionesMasivas')
                  ->daily(1, '03:00');
        $schedule->command('SolicitudesVencidas')
                  ->daily(1, '00:15');
        $schedule->command('EstadoAcuerdoPago')
                  ->daily(1, '00:15');
        $schedule->command('SuspensionTemporal')
                  ->monthlyOn(1, '00:01');

        //Argumentos Cron Windows
        # -f "C:\xampp\htdocs\citasmedicas\artisan"  CitasVencidas
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
