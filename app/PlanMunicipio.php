<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanMunicipio extends Model
{
    protected $fillable = 'planes_municipios';
    protected $table = ['proyecto_municipio_id','plan_comercial_id'];
}
