<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalisisV extends Model
{
    protected $table = 'analisisV';
    protected $fillable = ['TipoDeDocumento','Identificacion','nombre','Apellidos','estado','CasaApartamento','Casa','Barrio','SuperManzana','Manzana','Bloque','Interior','NombreEdificio_o_Conjunto','DireccionNomenclatura','DireccionDeCorrespondencia','TelefonoDeContactoFijo','TelefonoDeContactoMovil','Celular2','Correo2','CorreoElectronico','Estrato','Arrendatario','Propietario','VP','Otro','RelacionConElPredio','total_deuda','meses_mora','EstadoDelServicio','DescripcionPlan','VelocidadInternet','ValorDelServicio','fecha_contrato','FechaInstalacion','sn','NombreMunicipio','NombreDelDepartamento','estado_u2000','Latitud','Longitud', 'ClienteId'];

    public function cliente(){
    	return $this->belongsTo(Cliente::class, 'ClienteId');
    }

}
