<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoDocumento extends Model
{
  protected $table = 'proyectos_documentos';
  protected $fillable = [
    'documento_nombre_id',
    'documento_categoria_id',
    'confidencial',
    'versionado',
    'version',
    'fecha_expedicion',
    'fecha_vencimiento',
    'estado',
    'proyecto_id',
    'ruta',
    'tipo',
    'contenido_documento',
    'proyecto_documento_id'];

  public function proyecto(){
    return $this->belongsTo(Proyecto::class, 'proyecto_id');
  }

  public function documento_nombre(){
    return $this->belongsTo(DocumentoNombre::class);
  }

  public function documento_categoria(){
    return $this->belongsTo(DocumentoCategoria::class);
  }

  public function renovacion(){
    return $this->belongsTo(ProyectoDocumento::class, 'proyecto_documento_id', 'id');
  }

  public function renovaciones(){
    return $this->hasMany(ProyectoDocumento::class,'proyecto_documento_id');
  }

  public function scopeNombre($query, $nombre){
    if (!empty($nombre)) {

      $query->whereHas('documento_nombre', function ($query) use ($nombre){
          $query->where('nombre','like', '%'.$nombre.'%');
      })->orWhere('contenido_documento', 'like', '%'.$nombre.'%');
    }
  }


  public function scopeCategoria($query, $categoria,$subcategoria = null){

    if(!empty($subcategoria)){      
      $query->where('documento_categoria_id', $subcategoria);
    }else if(!empty($categoria)){

      $query->where(function($query) use ($categoria) {        
        $query->where('documento_categoria_id', $categoria);

        $query->orWhereHas('documento_categoria', function ($query) use($categoria) {          
          $query->where('documento_categoria_id', $categoria);         
        });
      });
    }
  }

  public function scopeEstado($query, $estado){
    if (!empty($estado)) {
      $query->where('estado','=', $estado);
    }
  }

}
