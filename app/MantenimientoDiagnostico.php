<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoDiagnostico extends Model
{
    protected $table = "MantenimientosDiagnosticos";
    protected $primaryKey = "ManDiagId";
    public $timestamps = false;
    /*protected $fillable = ['DiagnosticoId','FALLA_EN_SISTEMA_ELECTRICO_EN_NODO','FALLA_EN_UPS','FALLA_EN_PLANTA_ELECTRICA','FALLA_EN_SISTEMA_ELECTRICO_EN_OLT','FALLA_EN_ROUTER_NODO','FALLA_EN_ROUTER_VTD','FALLA_EN_OLT','FALLA_EN_MODULO_SFP_PON','AVERIA_EN_RED_Y_O_EQUIPOS_DEL_PROVEEDOR_ISP','CORTE_DE_FIBRA_OPTICA_EN_RED_PRIMARIA','CORTE_DE_FIBRA_OPTICA_EN_RED_SECUNDARIA','AVERIA_EN_EQUIPOS_DE_PLANTA_EXTERNA','AVERIA_POR_EVENTOS_NATURALES_TERREMOTOS__ETC','CORTE_DE_FIBRA_DROP_EN_LA_INSTALACION_EXTERNA_DEL_USUARIO','CORTE_DE_FIBRA_DROP_EN_LA_INSTALACION_INTERNA_DEL_USUARIO','ATENUACION_DE_POTENCIA_EN_CONECTORES_MECANICOS','FALLA_EN_MODULO_SFP_UPLINK','AVERIA_EN_PATCH_CORD_DE_FIBRA','AVERIA_EN_PATCH_CORD_UTP','ATENUACION_DE_POTENCIA_POR_MACROCURVATURA','FALLAS_EN_ONT_NO_SINCRONIZA__NO_ENCIENDE__PUERTO_AVERIADO','AVERIA_EN_ADAPTADOR_AC_DC_DE_LA_ONT','FALLA_EN_PORTATIL_DE_CONEXIONES_DIGITALES_ll','FALLA_EN_EQUIPO_DEL_USUARIO','VERIFICACION_DEL_SERVICIO_POR_FALLA_MASIVA','OTRO','MantId'];*/

    protected $fillable = ['MantId','ProgMantId','DiagnosticoId'];

    public function mantenimiento(){
        return $this->belongsTo(Mantenimiento::class, 'MantId');
    }

    public function diagnostico(){
        return $this->belongsTo(TipoFallo::class, 'DiagnosticoId','TipoFallaId');
    }



}
