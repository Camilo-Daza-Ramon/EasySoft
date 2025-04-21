<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'Clientes';
    protected $primaryKey = 'ClienteId';
    protected $fillable = [
        'Status',
        'ProyectoId',
        'UbicacionId',
        'Vendedor',
        'Fecha',
        'NumeroDeFormulario',
        'TipoDeDocumento',
        'Identificacion',
        'ExpedidaEn',
        'TarifaInternet',
        'ValorTarifaInternet',
        'CodigoDeCliente',
        'NombreBeneficiario',
        'Apellidos',
        'TelefonoDeContactoFijo',
        'TelefonoDeContactoMovil',
        'DireccionNomenclatura',
        'NombreEdificio_o_Conjunto',
        'Interior',
        'Bloque',
        'Manzana',
        'SuperManzana',
        'Barrio',
        'CasaApartamento',
        'Casa',
        'Departamento',
        'DireccionDeCorrespondencia',
        'CorreoElectronico',
        'Estrato',
        'VP',
        'Propietario',
        'Arrendatario',
        'Otro',
        'RelacionConElPredio',
        'UsoResidencial',
        'UsoComercial',
        'ContactoEmergencia',
        'EstadoEnElSistema',
        'Latitud',
        'Longitud',
        'Territorio',
        'Unidad',
        'Ciudad',
        'Clasificacion',
        'Verificado',
        'StatusCobranza',
        'MarcaTiempo',
        'CicloDeFacturacion',
        'Primaria',
        'Secundaria',
        'Superior',
        'PlanComercial',
        'DiaAgendamiento',
        'Hora',
        'FechaAgendamiento',
        'FechaInstalacion',
        'FechaAprobacion',
        'MotivoReagendamiento',
        'FechaAprobacionNoc',
        'PortatilAsignado',
        'SerialPortatil',
        'KitAsignado',
        'ContratistaAsignadoInstalacion',
        'ContratistaAsignadoEntregaPortatil',
        'FechaPreAprovisionamiente',
        'FechaComisionamiento',
        'SerialOnt',
        'OntName',
        'NombreVendedor',
        'HaceCuantoNoTieneInternet',
        'AutorizaFacturaElectronica',
        'AutorizaContratoPorCorreo',
        'FirmaCliente',
        'MesesResidenciasEnInmueble',
        'Imprimir',
        'CodigoMinVivenda',
        'MotivoDeRechazo',
        'ComentarioRechazo',
        'TipoDeUso',
        'TipoDeDocumentoOtorgante',
        'IdentificacionOtorgante',
        'ExpedidaEnOtorgante',
        'NombreOtorgante',
        'ArchivoHuella',
        'Velocidad',
        'AplicaTarifaSocial',
        'PC',
        'PróximaVisitaDía',
        'PróximaVisitaJornada',
        'OficinaFísica',
        'ValorPC',
        'Canal',
        'RedSocial',
        'FotoObligatoriaAnexoFotografico',
        'AnexoFotográfico2',
        'NúmeroDePersonasConEducacionPrimaria',
        'NúmeroPersonasConEducacionSecundaria',
        'NúmeroPersonasConEducacionUniversitaria',
        'Ubicación',
        'ArchivoNotificacion',
        'ArchivoActaEntrega',
        'ArchivoDeReciboSatisfaccion',
        'ArchivoCedula',
        'ArchivoCedulaCara2',
        'ArchivoRecibo',
        'ArchivoActa',
        'ArchivoPoder',
        'ArchivoCedulaApoderado',
        'ArchivoCedulaApoderadoC2',
        'ArchivoContrato',
        'ArchivoFirma',
        'ArchivoFirmaOtorgante',
        'ArchivoOtroDocumento',
        'Imei',
        'OntrackFechaCreacion',
        'OntrackId',
        'OntrackReingreso',
        'UsuarioModifico',
        'FechaModificacion',
        'OpcionModificacion',
        'Tx',
        'Rx',
        'SabeFirmar',
        'MacONT',
        'Poder1',
        'Poder2',
        'Apoderado1',
        'OntrackIdUsuario',
        'MotivoSubsanar',
        'FirmaOriginal',
        'ImeiOriginal',
        'UsuarioAprovisiono',
        'FechaFinDelServicio',
        'Revocada',
        'Antivirus',
        'TomadaDelCelular',
        'Celular2',
        'EmpresaFacturaID',
        'Meta',
        'BeneficiarioComputador',
        'Correo2',
        'TipoDePlancomercial',
        'FechaRenovacion',
        'EstadoDelServicio',
        'CobrosAdicionales',
        'Codigo_Postal',
        'sexo',
        'pertenencia_etnica',
        'genero',
        'nivel_estudios',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'discapacidad',
        'orientacion_sexual',
        'municipio_id',
        'created_at',
        'updated_at',
        'user_id',
        'auditor_id',
        'tipo_beneficiario',
        'reporte',
        'direccion_recibo',
        'zona',
        'localidad',
        'ComunidadID',
        'tipo_usuario'
    ];
    public static function validarLimiteUsuariosProyecto()
    {
        // Contar el número total de usuarios asociados al ProyectoId=14
        $totalUsuarios = self::where('ProyectoId', 14)->count();
    
        // Verificar que el total de usuarios no exceda el límite permitido
        if ($totalUsuarios >= 647) {
            throw new \Exception('Se ha alcanzado el límite de usuarios permitidos para el ProyectoId=14.');
        }
    
        // Retornar un mensaje de éxito si todo está correcto
        return 'El registro es permitido.';
    }
        
    public function comunidad()
    {
        return $this->belongsTo(Comunidad::class, 'ComunidadID');
    }


    public function facturacion(){
    	return $this->hasMany(Facturacion::class, 'ClienteId');
    }

    public function archivos(){
        return $this->hasMany(ArchivoCliente::class, 'ClienteId');
    }

    public function recaudo(){
    	return $this->hasMany(Recaudo::class);
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function ubicacion(){
    	return $this->belongsTo(Ubicacion::class, 'UbicacionId');
    }

    public function plancomercial(){
        return $this->belongsTo(PlanComercial::class, 'PlanComercial');
    }    

    public function tikect(){
        return $this->hasMany(Ticket::class, 'ClienteId');
    }

    public function auditor(){
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function vendedor(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'ProyectoId');
    }

    public function analisis(){
        return $this->hasOne(AnalisisV::class, 'ClienteId');
    }

    public function cliente_ont_olt(){
        return $this->hasOne(ClienteOntOlt::class, 'ClienteId');
    }

    public function contrato(){
        return $this->hasMany(ClienteContrato::class, 'ClienteId');
    }

    public function instalacion(){
        return $this->hasOne(Instalacion::class, 'ClienteId');
    }

    public function instalaciones(){
        return $this->hasMany(Instalacion::class, 'ClienteId');
    }

    public function meta_cliente(){
        return $this->hasOne(MetaCliente::class, 'ClienteId');
    }

    public function reemplazo(){
        return $this->hasOne(ClienteReemplazo::class, 'cliente_nuevo_id');
    }

    public function historial_factura_pago(){
        return $this->hasOne(HistorialFacturaPagoV::class, 'ClienteId');
    }

    public function softv(){
        return $this->hasOne(SoftvClienteV::class, 'ClienteId');
    }

    public function novedad(){
        return $this->hasMany(Novedad::class, 'ClienteId');
    } 

    public function atencion_cliente(){
        return $this->hasMany(AtencionCliente::class, 'cliente_id')->orderBy('id','DESC');
    }

    public function mantenimiento(){
        return $this->hasMany(Mantenimiento::class, 'ClienteId');
    }

    public function mantenimientos_masivos(){
        return $this->hasMany(MantenimientoProgramacionClientes::class, 'ClienteId');
    }

    public function pqr(){
        return $this->hasMany(PQR::class, 'ClienteId')
            ->orWhere('IdentificacionCliente', $this->Identificacion);

    }

    public function suspension()
    {
        return $this->hasOne(ClienteSuspension::class, 'cliente_id');
    }

    public function campana_cliente()
    {
        return $this->hasMany(CampanaClientes::class ,'cliente_id');
    }

    public function acuerdo_pago()
    {
        return $this->hasMany(AcuerdoPago::class ,'cliente_id');
    }


    public function restriccion(){
        return $this->hasOne(ClienteRestriccion::class, 'cliente_id');
    }

    public function ultima_factura(){
        return $this->hasOne(UltimaFacturaV::class, 'cliente_id');
    }

    public function suspensiones_temporales()
    {
        return $this->hasMany(SuspensionTemporal::class ,'cliente_id');
    }

    public function proyectos_preguntas_respuestas(){
        return $this->hasMany(ProyectoPreguntaRespuesta::class ,'cliente_id');
    }


    public function scopeCedula($query, $cedula){
        if (!empty($cedula)) {
            $query->where('Clientes.Identificacion', $cedula);
        }
    }

    public function scopePalabra($query, $palabra){
        if (!empty($palabra)) {

            $caracter = str_replace([" ", "."], "", $palabra);

            if(filter_var($palabra, FILTER_VALIDATE_EMAIL)){
                $query->where('CorreoElectronico', 'like', '%'.$palabra.'%'); 
            } elseif (is_numeric($caracter)) {
                $query->where('ClienteId','=', $caracter)
                ->orWhere('Identificacion','=', $caracter)
                ->orWhere('TelefonoDeContactoMovil', $caracter);
            } else{
                $query->where('NombreBeneficiario','like', '%'.$palabra.'%');
            }                           
        }
    }

    public function scopeProyecto($query, $proyecto){
        if (!empty($proyecto)) {
            $query->where('Clientes.ProyectoId', $proyecto);            
        }
    }

    public function scopeDepartamento($query, $departamento){
        if (!empty($departamento)) {
            $query->whereHas('municipio', function ($query) use ($departamento){
                $query->where('Municipios.DeptId', $departamento);
            });
        }
    }

    public function scopeMunicipio($query, $municipio){
        if (!empty($municipio)) {
            $query->where('Clientes.municipio_id', $municipio);
            
        }
    }    

    public function scopeEstado($query, $estado){
        if (!empty($estado)) {
            $query->where('Clientes.Status',  $estado );
        }
    }

    public function scopeAccion($query, $tipo_accion){
        if ($tipo_accion == 'REACTIVAR') {            
            $query->whereHas('historial_factura_pago', function ($query){
                $query->where([['Clientes.Status', 'ACTIVO'],['EstadoDelServicio', 'Suspendido'],['total_deuda', '<=', 0]]);
            });
        }elseif($tipo_accion == 'SUSPENDER') {            
            $query->whereHas('historial_factura_pago', function ($query){
                $query->whereRaw("Clientes.Status = 'ACTIVO' AND EstadoDelServicio = 'Activo' AND total_deuda > (TarifaInternet * 2)");
            });
        }elseif($tipo_accion == 'SUSPENDIDOS') {
            $query->where("EstadoDelServicio", 'Suspendido');
        }
        
    }

    /*public function activo(){
        return $this->hasOne(ActivoFijo::class, 'ClienteId');
    }*/



    

    /*public function activo(){
        return $this->belongsToMany(ActivoFijo::class,'clientes_onts_olts', 'ClienteId', 'ActivoFijoId')
            ->withPivot('ClienteId', 'id');
    }

    public function olt(){
        return $this->belongsToMany(Olt::class,'clientes_onts_olts')
            ->withPivot('ActivoFijoId');
    }

    public function user(){
        return $this->belongsToMany(User::class,'clientes_onts_olts', 'dfsd', 'user_id')
            ->withPivot('id');
    }*/

}
