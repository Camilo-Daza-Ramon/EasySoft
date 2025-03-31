<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CamposVisualizar extends Model
{
    //
    protected $table = 'campos_visualizar';
    protected $fillable = [ 
        'campo',
        'campana_id', 
    ];
    public function campos_visualizar()
    {
        return $this->belongsTo(Campana::class ,'campana_id');
    }
   
}
