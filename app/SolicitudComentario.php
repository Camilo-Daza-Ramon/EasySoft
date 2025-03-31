<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudComentario extends Model
{
    protected $table = "solicitudes_comentarios";
    protected $fillable = ['comentario','user_id','solicitud_id'];

    public function solicitud(){
      return $this->belongsTo(Solicitud::class);
    }

    public function user(){
      return $this->belongsTo(User::class);
    }
}
