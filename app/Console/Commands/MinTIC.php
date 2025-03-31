<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Custom\WebService;

class MinTIC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportarMinTIC {municipio*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reportar los clientes del proyecto LP15-CENTRO al MinTIC por medio de JWT';

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

        $municipio = $this->argument('municipio');

        $result = array();

        $reportar = new WebService;

        if ($municipio[0] == 0) {            
            $result[] = $reportar->clientes_sin_onts();
            $result[] = $reportar->clientes_no_activos();
            $result[] = $reportar->clientes_reemplazados();
        }else{
            $result[] = $reportar->listar_cliente($municipio[0]);
        }
        
        print_r($result);

    }
}

#php -f "D:\Awebsites\ConstruyendoWebSite\easy\artisan" reportarMinTIC {municipio}
