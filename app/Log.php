<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $fillable = ['tabla','accion','descripcion','user_id'];

    public function user(){
      return $this->belongsTo(User::class);
    }
}
