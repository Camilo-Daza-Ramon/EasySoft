<?php 

namespace App\Custom;
use App\ClienteContrato;
use App\ProyectoClausula;
use Storage;

class Data
{
	public function contrato($id){

        $data = [
            'contrato' => '',
            'valor_pagar' => '_______',
            'cantidad_megas' => '____',
            'vigencia' => '__',
            'fecha_contrato' => '__________',
            'fecha_activacion' => '',
            'numero_contrato' => '0',
            'fecha_instalacion' => '',
            'nombre_suscriptor' => '',
            'identificacion' => '',
            'tipo_documento' => 'C.C',
            'documento_expedicion' => '',
            'correo' => '',
            'telefono' => '',
            'direccion' => '',
            'barrio' => '',
            'estrato' => '',
            'departamento' => '',
            'municipio' => '',
            'tipo_beneficiario' => '',
            'asesor_comercial' => '',
            'firma' => '',
            'proyecto' => '',
            'servicios' => array(),
            'tipo_cobro' => '__________',
            'clausulas_permanencia' => array(),
            'costos' => array(),
            'reconexion' => 0,
            'dia_corte_facturacion' => 0,
            'condiciones_plan' => '',
            'condiciones_servicio' => '',
        ];

        if (!empty($id)) {

            $contrato = ClienteContrato::findOrFail($id);
            $clausula_permanencias = ProyectoClausula::where('proyecto_id', $contrato->cliente->ProyectoId)->get();
            //$proyecto->clausula;


            /*if ($contrato->cliente->ProyectoId == 6) {
               $data['contrato'] = $contrato->cliente->softv->contrato . '-2';
            }else{
                $data['contrato'] = $contrato->referencia;
            }*/

            $data['contrato'] = $contrato->referencia;
            

            $data['servicios'] = $contrato->servicio;
            $data['numero_contrato'] = $contrato->referencia;
            $data['fecha_contrato'] = $contrato->fecha_inicio;
            $data['fecha_instalacion'] = $contrato->fecha_instalacion;
            $data['nombre'] = $contrato->cliente->NombreBeneficiario;
            $data['apellido'] = $contrato->cliente->Apellidos;

            $total_valor_contrato = 0;
            $cantidad_megas = 0;

            foreach ($contrato->servicio as $servicio) {
                $total_valor_contrato += $total_valor_contrato + $servicio->valor;
                $cantidad_megas = $servicio->cantidad;
            }

            #Plan Comercial
            $data['valor_pagar'] = number_format($total_valor_contrato, 0, ',','.');
            $data['cantidad_megas'] = number_format($cantidad_megas, 0, ',','.');
            $data['vigencia'] = $contrato->vigencia_meses;
            $data['tipo_cobro'] = $contrato->tipo_cobro;

            $data['nombre_suscriptor'] = $contrato->cliente->NombreBeneficiario . ' ' .$contrato->cliente->Apellidos;
            $data['identificacion'] = $contrato->cliente->Identificacion;
            $data['tipo_documento'] = $contrato->cliente->TipoDeDocumento;
            $data['documento_expedicion'] = $contrato->cliente->ExpedidaEn;
            $data['correo'] = $contrato->cliente->CorreoElectronico;
            $data['telefono'] = $contrato->cliente->TelefonoDeContactoMovil;
            $data['direccion'] = (empty($contrato->cliente->direccion_recibo))? $contrato->cliente->DireccionDeCorrespondencia : $contrato->cliente->direccion_recibo;
            $data['barrio'] = $contrato->cliente->Barrio;
            $data['estrato'] = $contrato->cliente->Estrato;
            $data['departamento'] = $contrato->cliente->municipio->departamento->NombreDelDepartamento;
            $data['municipio'] = $contrato->cliente->municipio->NombreMunicipio;
            $data['tipo_beneficiario'] = $contrato->cliente->tipo_beneficiario;

            $data['proyecto'] = $contrato->cliente->ProyectoId;
            $data['asesor_comercial'] = $contrato->vendedor->name; //$contrato->cliente->vendedor->name;

            $data['costos'] = $contrato->cliente->proyecto->costo;
            $data['clausulas_permanencia'] = $clausula_permanencias;
            $data['condiciones_plan'] = $contrato->cliente->proyecto->condiciones_plan;
            $data['condiciones_servicio'] = $contrato->cliente->proyecto->condiciones_servicio;

            foreach ($contrato->cliente->archivos as $archivo) {   

                $existe = Storage::disk('public')->exists($archivo->archivo);

                if ($archivo->nombre == 'firma' && $existe) {
                    $data['firma'] = $archivo->archivo;
                }
            }
        }

        return $data;
    }

    public function factura_electronica($tipo,$tipo_concepto,$tipo_operacion,$tipo_negociacion,$tipo_medio_pago,$documento_relacionado,$fecha_vencimiento,$valor_total,$total_impuestos, $informacion_adicional,$departamento,$ciudad,$cedula,$nombres,$apellidos,$correo,$telefono,$direccion,$detalles,$datos_adicionales,$porcentaje_descuento,$motivo_descuento,$fecha_expedision){

    	$nota_reporte =
    	array(
            'TipoDocumento'     => $tipo, #Indica el Tipo de Documento que se está reportando
            'TipoConcepto'      => $tipo_concepto, #Cuando es una Factura: Texto vacío.
            'TipoOperacion'     => $tipo_operacion,
            'TipoNegociacion'   => $tipo_negociacion, #1:Contado - 2:Credito
            'MedioPago'         => $tipo_medio_pago, #Instrumento no Definido
            'DatosAdicionales'  => $datos_adicionales, #Informacion de los campos adicionales
            'DocumentoRelacionado' => (string)$documento_relacionado,//,
            'FechaExpedicion' => date("Y-m-d h:i:s"),//date_format(date_create($fecha_expedision),'Y-m-d h:i:s'),
            'FechaVencimiento' => $fecha_vencimiento,
            'Total' => (double)$valor_total,
            'TotalImpuestos' => (double)$total_impuestos,
            'TotalRetenciones' => 0,
            'DescuentoGeneralPorcentaje' => $porcentaje_descuento,
            'DescuentoGeneralMotivo' => $motivo_descuento,
            'InfoAdicional' => $informacion_adicional.' <p style="text-align:center;">Señor usuario, la Superintendencia de Industria y Comercio -SIC-, es la autoridad que ejerce las funciones de inspección, vigilancia y control sobre los servicios prestados por el (los) proveedor(es) respectivo(s). <br> <span style="font-size: 12px; border: 1px solid #000; padding:2px; border-radius: 3px;">Pagar en Efecty convenio: 111008</span></p>',
            'Adquiriente' => array(
                'TipoIdentificacion' => '13',
                'Ciudad' => $departamento .''. $ciudad,
                'TipoOrganizacion' => '2', #Persona natural
                'TipoRegimen' => '49', #No responsable de IVA
                'Identificacion' => $cedula,
                'Nombre' => mb_convert_case($nombres, MB_CASE_TITLE, "UTF-8"),
                'Apellido' => mb_convert_case($apellidos, MB_CASE_TITLE, "UTF-8"),
                'CorreoElectronico' => strtolower($correo), //'trabajosdecolombia@hotmail.com',
                'Telefono' => $telefono,
                'Direccion' => $direccion
            ),
            'Detalle' => $detalles
        );

        return $nota_reporte;

    }

    public function facturaAddDetalles($concepto, $cantidad,$valor,$total_impuestos,$total,$valor_iva,$total_iva){
        $array = 
            array(
                'Descripcion' => $concepto,
                'Cantidad' => (double)$cantidad,
                'Valor' => (double)$valor,
                'TotalImpuestos' => (double)$total_impuestos,
                'TotalRetenciones' => 0,
                'Total' => (double)$total,                
                'Impuestos' => array(
                    array(
                        'Codigo' => '01',
                        'Valor' => (double)$valor_iva,
                        'Total' => (double)$total_iva,
                    )
                ),
                'Retenciones' => array()
            );

        return $array;
    }

    private function facturaAddAnticipos($concepto, $valor){
        $array = array(
            'FechaHora' => date('Y-m-d') . ' 12:00:00', 
            'Valor' => (double)$valor, 
            'Comentario' => $concepto
        );

        return $array;
    }

    private function crear_correo($nombre,$apellido){

        $especiales = array('Á','É','Í','Ó','Ú','á','é','í','ó','ú', ' ' ,'ñ');

        $servidor_correo = array('@gmail.com', '@outlook.com', '@hotmail.com');
        $caracteres = array('_','.','-');
        $numero = random_int(1, 100);

        $correo = str_replace($especiales, '', $nombre).$caracteres[random_int(0, 2)].str_replace($especiales, '', $apellido).$numero.$servidor_correo[random_int(0, 2)];

        return strtolower($correo);
    }
}