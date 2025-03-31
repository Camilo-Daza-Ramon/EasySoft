<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonitoreoCliente extends Model
{
    protected $table = 'monitoreos_clientes';
    protected $fillable = ['fsp','ont_id','control_flag','run_state','config_state','match_state','last_down_cause','last_up_time','last_down_time','last_dying_gasp_time','cliente_id','ultima_ejecucion_script'];

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
